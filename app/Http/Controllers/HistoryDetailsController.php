<?php

namespace App\Http\Controllers;

use App\Http\Utils\BasicUtil;
use App\Http\Utils\BusinessUtil;
use App\Http\Utils\ErrorUtil;
use App\Http\Utils\UserActivityUtil;
use App\Models\AttendanceHistory;
use App\Models\Department;
use App\Models\EmployeeAddressHistory;
use App\Models\EmployeePassportDetailHistory;
use App\Models\EmployeeProjectHistory;
use App\Models\EmployeeRightToWork;

use App\Models\EmployeeSponsorshipHistory;

use App\Models\EmployeePensionHistory;


use App\Models\EmployeeUserWorkShiftHistory;

use App\Models\EmployeeVisaDetailHistory;
use App\Models\EmployeeRightToWorkHistory;

use App\Models\WorkShiftHistory;
use App\Models\LeaveHistory;
use App\Models\UserAssetHistory;
use Exception;
use Illuminate\Http\Request;

class HistoryDetailsController extends Controller
{
    use ErrorUtil, UserActivityUtil, BusinessUtil, BasicUtil;

    /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-assets",
     *      operationId="getUserAssetHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *   *   * *  @OA\Parameter(
     * name="user_asset_id",
     * in="query",
     * description="user_asset_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get asset history",
     *      description="This method is to get asset history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserAssetHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $user_asset_history = UserAssetHistory::
             with([
                "user" => function ($query) {
                    $query->select('users.id', 'users.first_Name','users.middle_Name',
                    'users.last_Name');
                },
             ])
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('user_asset_histories.user_id', $request->user_id);
            })
            ->when(!empty($request->user_asset_id), function ($query) use ($request) {
                return $query->where('user_asset_histories.user_asset_id', $request->user_asset_id);
            })
            ->whereHas("user.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                        $term = $request->search_key;
                         $query->whereHas('user_asset',function($query) use ($term) {

                       return   $query->where(function($query) use ($term) {

                                $query->where("user_assets.name", "like", "%" . $term . "%")
                                ->orWhere("user_assets.code", "like", "%" . $term . "%")
                                ->orWhere("user_assets.serial_number", "like", "%" . $term . "%")
                                ->orWhere("user_assets.type", "like", "%" . $term . "%");


                            });


                         });


                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('user_asset_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('user_asset_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("user_asset_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("user_asset_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($user_asset_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }


   /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-passport-details",
     *      operationId="getUserPassportDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee passport history",
     *      description="This method is to get passport history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserPassportDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_passport_details_history = EmployeePassportDetailHistory::where(["is_manual" => 0])

             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_passport_detail_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_passport_detail_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                         $query->where("employee_passport_detail_histories.passport_number", "like", "%" . $term . "%")
                             ->orWhere("employee_passport_detail_histories.place_of_issue", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_passport_detail_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_passport_detail_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_passport_detail_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_passport_detail_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_passport_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }

     /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-visa-details",
     *      operationId="getUserVisaDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee visa history",
     *      description="This method is to get visa history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserVisaDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $all_manager_department_ids = $this->get_all_departments_of_manager();


             $employee_visa_details_history = EmployeeVisaDetailHistory::where(["is_manual" => 0])
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_visa_detail_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_visa_detail_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })

                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                         $query->where("employee_visa_detail_histories.BRP_number", "like", "%" . $term . "%")
                             ->orWhere("employee_visa_detail_histories.place_of_issue", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_visa_detail_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_visa_detail_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_visa_detail_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_visa_detail_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_visa_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }


 /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-right-to-works",
     *      operationId="getRightToWorksHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee right to work history",
     *      description="This method is to get right to work history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getRightToWorksHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $all_manager_department_ids = $this->get_all_departments_of_manager();


             $employee_right_to_work_histories = EmployeeRightToWorkHistory::where(["is_manual" => 0])
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_right_to_work_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_right_to_work_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })

                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                         $query->where("employee_right_to_work_histories.right_to_work_code", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_right_to_work_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_right_to_work_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_right_to_work_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_right_to_work_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_right_to_work_histories, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }

       /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-sponsorship-details",
     *      operationId="getUserSponsorshipDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee sponsorship history",
     *      description="This method is to get sponsorship history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserSponsorshipDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

             $all_manager_department_ids = $this->get_all_departments_of_manager();


             $employee_sponsorship_details_history = EmployeeSponsorshipHistory::where(["is_manual" => 0])

             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_sponsorship_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_sponsorship_histories.user_id', $request->user()->id);
            })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                         $query->where("employee_sponsorship_histories.certificate_number", "like", "%" . $term . "%");
                     });
                 })
                 ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                    $query->whereIn("departments.id",$all_manager_department_ids);
                 })
                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_sponsorship_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_sponsorship_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_sponsorship_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_sponsorship_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_sponsorship_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }
   /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-pension-details",
     *      operationId="getUserPensionDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee pension history",
     *      description="This method is to get pension history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserPensionDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }


              $all_manager_department_ids = $this->get_all_departments_of_manager();



             $employee_pension_details_history = EmployeePensionHistory::where(["is_manual" => 0])

             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_pension_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_pension_histories.user_id', $request->user()->id);
            })
                //  ->when(!empty($request->search_key), function ($query) use ($request) {
                //      return $query->where(function ($query) use ($request) {
                //          $term = $request->search_key;
                //          $query->where("employee_sponsorship_histories.certificate_number", "like", "%" . $term . "%");
                //      });
                //  })
                 ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                    $query->whereIn("departments.id",$all_manager_department_ids);
                 })
                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_pension_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_pension_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_pension_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_pension_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_pension_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }
       /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-address-details",
     *      operationId="getUserAddressDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee address history",
     *      description="This method is to get address history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserAddressDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }


             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_address_details_history = EmployeeAddressHistory::where(["is_manual" => 0])
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_address_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('employee_address_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                         $query->where("employee_address_histories.address_line_1", "like", "%" . $term . "%");
                         $query->orWhere("employee_address_histories.address_line_2", "like", "%" . $term . "%");
                         $query->orWhere("employee_address_histories.country", "like", "%" . $term . "%");
                         $query->orWhere("employee_address_histories.city", "like", "%" . $term . "%");
                         $query->orWhere("employee_address_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_address_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_address_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_address_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_address_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_address_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }

          /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-attendance-details",
     *      operationId="getUserAttendanceDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *      *   * *  @OA\Parameter(
     * name="attendance_id",
     * in="query",
     * description="attendance_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee attendance history",
     *      description="This method is to get attendance history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserAttendanceDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_attendance_details_history = AttendanceHistory::
             with([
                "employee" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "approved_by_users.actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "rejected_by_users.actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },

                "work_location",
                "projects",


             ])
             ->when(!empty($request->attendance_id), function ($query) use ($request) {
                return $query->where('attendance_histories.attendance_id', $request->attendance_id);
            })
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('attendance_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('attendance_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                        //  $query->where("attendance_histories.address_line_1", "like", "%" . $term . "%");
                        //  $query->orWhere("attendance_histories.address_line_2", "like", "%" . $term . "%");
                        //  $query->orWhere("attendance_histories.country", "like", "%" . $term . "%");
                        //  $query->orWhere("attendance_histories.city", "like", "%" . $term . "%");
                        //  $query->orWhere("attendance_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('attendance_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('attendance_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("attendance_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("attendance_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_attendance_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }

         /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-leave-details",
     *      operationId="getUserLeaveDetailsHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *      *   * *  @OA\Parameter(
     * name="leave_id",
     * in="query",
     * description="leave_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee leave history",
     *      description="This method is to get leave history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserLeaveDetailsHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_leave_details_history = LeaveHistory::with(
                [
                "records",
                "employee" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "approved_by_users.actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                "rejected_by_users.actor" => function ($query) {
                    $query->select(
                        'users.id',
                        'users.first_Name',
                        'users.middle_Name',
                        'users.last_Name'
                    );
                },
                ]

                )
             ->when(!empty($request->leave_id), function ($query) use ($request) {
                return $query->where('leave_histories.leave_id', $request->leave_id);
            })
             ->when(!empty($request->user_id), function ($query) use ($request) {
                return $query->where('leave_histories.user_id', $request->user_id);
            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                return $query->where('leave_histories.user_id', $request->user()->id);
            })
            ->whereHas("employee.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                        //  $query->where("leave_histories.address_line_1", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.address_line_2", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.country", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.city", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('leave_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('leave_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("leave_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("leave_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_leave_details_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }


           /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-work-shift",
     *      operationId="getUserWorkShiftHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *      *   * *  @OA\Parameter(
     * name="leave_id",
     * in="query",
     * description="leave_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee work shift history",
     *      description="This method is to get work shift history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserWorkShiftHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_work_shift_history = WorkShiftHistory::
                with([
                  "details",
                  "user_work_shift" => function($query) use($request) {
                    $query->when(empty($request->user_id), function ($query) use ($request) {
                           $query->where('employee_user_work_shift_histories.user_id', auth()->user()->id);
                   })
                   ->when(!empty($request->user_id), function ($query) use ($request) {
                        $query->where('employee_user_work_shift_histories.user_id', $request->user_id);
               }) ;





                  }
                ])

                ->when(!empty($request->user_id), function ($query) use ($request) {

                 $query->whereHas('users',function($query) use($request) {
                     $query->where('employee_user_work_shift_histories.user_id', $request->user_id);
                });


            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                 $query->whereHas('users',function($query) use($request) {
                    $query->where('employee_user_work_shift_histories.user_id', auth()->user()->id);
               });
            })
            // ->whereHas("users.department_user.department", function($query) use($all_manager_department_ids) {
            //     $query->whereIn("departments.id",$all_manager_department_ids);
            //  })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                        //  $query->where("leave_histories.address_line_1", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.address_line_2", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.country", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.city", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('work_shift_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('work_shift_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("work_shift_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("work_shift_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });


             return response()->json($employee_work_shift_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }
       /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/employee-work-shift",
     *      operationId="getEmployeeWorkShiftHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *      *   * *  @OA\Parameter(
     * name="leave_id",
     * in="query",
     * description="leave_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee work shift history",
     *      description="This method is to get work shift history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getEmployeeWorkShiftHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_work_shift_history = EmployeeUserWorkShiftHistory::
                with([
                    "work_shift_history"
                ])

                ->when(!empty($request->user_id), function ($query) use ($request) {

                // return $query->whereHas('users',function($query) use($request) {
                //      $query->where('employee_user_work_shift_histories.user_id', $request->user_id);
                // });
                $query->where('employee_user_work_shift_histories.user_id', $request->user_id);


            })
            ->when(empty($request->user_id), function ($query) use ($request) {
            //     return $query->whereHas('users',function($query) use($request) {
            //         $query->where('employee_user_work_shift_histories.user_id', auth()->user()->id);
            //    });
               $query->where('employee_user_work_shift_histories.user_id', auth()->user()->id);
            })
            ->whereHas("user.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                        //  $query->where("leave_histories.address_line_1", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.address_line_2", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.country", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.city", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('from_date', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('to_date', "<=", ($request->end_date . ' 23:59:59'))
                     ->WhereHas("work_shift_history",function($query) use($request) {
                        $query->where('work_shift_histories.to_date', "<=", ($request->end_date . ' 23:59:59'));
                     });
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_user_work_shift_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_user_work_shift_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });



             return response()->json($employee_work_shift_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }

    /**
     *
     * @OA\Get(
     *      path="/v1.0/histories/user-project",
     *      operationId="getUserProjectHistory",
     *      tags={"histories"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),

     *      * *  @OA\Parameter(
     * name="start_date",
     * in="query",
     * description="start_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="end_date",
     * in="query",
     * description="end_date",
     * required=true,
     * example="2019-06-29"
     * ),
     * *  @OA\Parameter(
     * name="search_key",
     * in="query",
     * description="search_key",
     * required=true,
     * example="search_key"
     * ),
     *   * *  @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="user_id",
     * required=true,
     * example="1"
     * ),
     *      *   * *  @OA\Parameter(
     * name="leave_id",
     * in="query",
     * description="leave_id",
     * required=true,
     * example="1"
     * ),
     *
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get employee work shift history",
     *      description="This method is to get work shift history",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

     public function getUserProjectHistory(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('user_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $all_manager_department_ids = $this->get_all_departments_of_manager();

             $employee_project_history = EmployeeProjectHistory::when(!empty($request->user_id), function ($query) use ($request) {
                $query->where('employee_project_histories.user_id', $request->user_id);

            })
            ->when(empty($request->user_id), function ($query) use ($request) {
                $query->where('employee_project_histories.user_id', auth()->user()->id);
            })
            ->whereHas("user.department_user.department", function($query) use($all_manager_department_ids) {
                $query->whereIn("departments.id",$all_manager_department_ids);
             })
                 ->when(!empty($request->search_key), function ($query) use ($request) {
                     return $query->where(function ($query) use ($request) {
                         $term = $request->search_key;
                        //  $query->where("leave_histories.address_line_1", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.address_line_2", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.country", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.city", "like", "%" . $term . "%");
                        //  $query->orWhere("leave_histories.postcode", "like", "%" . $term . "%");
                     });
                 })

                 ->when(!empty($request->start_date), function ($query) use ($request) {
                     return $query->where('employee_project_histories.created_at', ">=", $request->start_date);
                 })
                 ->when(!empty($request->end_date), function ($query) use ($request) {
                     return $query->where('employee_project_histories.created_at', "<=", ($request->end_date . ' 23:59:59'));
                 })
                 ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                     return $query->orderBy("employee_project_histories.id", $request->order_by);
                 }, function ($query) {
                     return $query->orderBy("employee_project_histories.id", "DESC");
                 })
                 ->when(!empty($request->per_page), function ($query) use ($request) {
                     return $query->paginate($request->per_page);
                 }, function ($query) {
                     return $query->get();
                 });;



             return response()->json($employee_project_history, 200);
         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }



}
