<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetIdRequest;
use App\Http\Requests\JobPlatformCreateRequest;
use App\Http\Requests\JobPlatformUpdateRequest;
use App\Http\Utils\BusinessUtil;
use App\Http\Utils\ErrorUtil;
use App\Http\Utils\UserActivityUtil;
use App\Models\Business;
use App\Models\DisabledJobPlatform;
use App\Models\JobListing;
use App\Models\JobListingJobPlatforms;
use App\Models\JobPlatform;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobPlatformController extends Controller
{
    use ErrorUtil, UserActivityUtil, BusinessUtil;
    /**
     *
     * @OA\Post(
     *      path="/v1.0/job-platforms",
     *      operationId="createJobPlatform",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to store job platform",
     *      description="This method is to store job platform",
     *
     *  @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
 * @OA\Property(property="name", type="string", format="string", example="tttttt"),
 * @OA\Property(property="description", type="string", format="string", example="erg ear ga&nbsp;")
 *
 *
     *
     *         ),
     *      ),
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

    public function createJobPlatform(JobPlatformCreateRequest $request)
    {
        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");
            return DB::transaction(function () use ($request) {
                if (!$request->user()->hasPermissionTo('job_platform_create')) {
                    return response()->json([
                        "message" => "You can not perform this action"
                    ], 401);
                }

                $request_data = $request->validated();
                $request_data["is_active"] = 1;
                $request_data["is_default"] = 0;
                $request_data["created_by"] = $request->user()->id;
                $request_data["business_id"] = $request->user()->business_id;

                if (empty($request->user()->business_id)) {
                    $request_data["business_id"] = NULL;
                    if ($request->user()->hasRole('superadmin')) {
                        $request_data["is_default"] = 1;
                    }
                }




                // $request_data["business_id"] = NULL;
                // $request_data["is_active"] = 1;
                // $request_data["is_default"] = 1;

                // $request_data["created_by"] = $request->user()->id;

                // if ($request->user()->hasRole('superadmin')) {
                //     $request_data["business_id"] = NULL;
                // $request_data["is_active"] = 1;
                // $request_data["is_default"] = 1;
                // $request_data["created_by"] = $request->user()->id;
                // }
                // else {
                //     $request_data["business_id"] = $request->user()->business_id;
                //     $request_data["is_active"] = 1;
                //     $request_data["is_default"] = 0;
                //     // $request_data["created_by"] = $request->user()->id;
                // }




                $job_platform =  JobPlatform::create($request_data);




                return response($job_platform, 201);
            });
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $this->sendError($e, 500, $request);
        }
    }

    /**
     *
     * @OA\Put(
     *      path="/v1.0/job-platforms",
     *      operationId="updateJobPlatform",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to update job platform ",
     *      description="This method is to update job platform",
     *
     *  @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
*      @OA\Property(property="id", type="number", format="number", example="Updated Christmas"),
 * @OA\Property(property="name", type="string", format="string", example="tttttt"),
 * @OA\Property(property="description", type="string", format="string", example="erg ear ga&nbsp;")


     *
     *         ),
     *      ),
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

    public function updateJobPlatform(JobPlatformUpdateRequest $request)
    {

        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");
            return DB::transaction(function () use ($request) {
                if (!$request->user()->hasPermissionTo('job_platform_update')) {
                    return response()->json([
                        "message" => "You can not perform this action"
                    ], 401);
                }
                // $business_id =  $request->user()->business_id;
                $request_data = $request->validated();



                $job_platform_query_params = [
                    "id" => $request_data["id"],
                    // "business_id" => $business_id
                ];


                // if ($request->user()->hasRole('superadmin')) {
                //     if(!($job_platform_prev->business_id == NULL && $job_platform_prev->is_default == 1)) {
                //         return response()->json([
                //             "message" => "You do not have permission to update this job platform due to role restrictions."
                //         ], 403);
                //     }

                // }
                // else {
                //     if(!($job_platform_prev->business_id == $request->user()->business_id)) {
                //         return response()->json([
                //             "message" => "You do not have permission to update this job platform due to role restrictions."
                //         ], 403);
                //     }
                // }
                $job_platform  =  tap(JobPlatform::where($job_platform_query_params))->update(
                    collect($request_data)->only([
                        'name',
                        'description',
                         // "is_default",
                        // "is_active",
                        // "business_id",

                    ])->toArray()
                )
                    // ->with("somthing")

                    ->first();
                if (!$job_platform) {
                    return response()->json([
                        "message" => "something went wrong."
                    ], 500);
                }

                return response($job_platform, 201);
            });
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $this->sendError($e, 500, $request);
        }
    }

  /**
     *
     * @OA\Put(
     *      path="/v1.0/job-platforms/toggle-active",
     *      operationId="toggleActiveJobPlatform",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to toggle job platform",
     *      description="This method is to toggle job platform",
     *
     *  @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(

     *           @OA\Property(property="id", type="string", format="number",example="1"),
     *
     *         ),
     *      ),
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

     public function toggleActiveJobPlatform(GetIdRequest $request)
     {

         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('job_platform_activate')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }
             $request_data = $request->validated();

             $job_platform =  JobPlatform::where([
                 "id" => $request_data["id"],
             ])
                 ->first();
             if (!$job_platform) {

                 return response()->json([
                     "message" => "no data found"
                 ], 404);
             }
             $should_update = 0;
             $should_disable = 0;
             if (empty(auth()->user()->business_id)) {

                 if (auth()->user()->hasRole('superadmin')) {
                     if (($job_platform->business_id != NULL || $job_platform->is_default != 1)) {

                         return response()->json([
                             "message" => "You do not have permission to update this job platform due to role restrictions."
                         ], 403);
                     } else {
                         $should_update = 1;
                     }
                 } else {
                     if ($job_platform->business_id != NULL) {

                         return response()->json([
                             "message" => "You do not have permission to update this job platform due to role restrictions."
                         ], 403);
                     } else if ($job_platform->is_default == 0) {

                         if($job_platform->created_by != auth()->user()->id) {

                             return response()->json([
                                 "message" => "You do not have permission to update this job platform due to role restrictions."
                             ], 403);
                         }
                         else {
                             $should_update = 1;
                         }



                     }
                     else {
                      $should_disable = 1;

                     }
                 }
             } else {
                 if ($job_platform->business_id != NULL) {
                     if (($job_platform->business_id != auth()->user()->business_id)) {

                         return response()->json([
                             "message" => "You do not have permission to update this job platform due to role restrictions."
                         ], 403);
                     } else {
                         $should_update = 1;
                     }
                 } else {
                     if ($job_platform->is_default == 0) {
                         if ($job_platform->created_by != auth()->user()->created_by) {

                             return response()->json([
                                 "message" => "You do not have permission to update this job platform due to role restrictions."
                             ], 403);
                         } else {
                             $should_disable = 1;

                         }
                     } else {
                         $should_disable = 1;

                     }
                 }
             }

             if ($should_update) {
                 $job_platform->update([
                     'is_active' => !$job_platform->is_active
                 ]);
             }

             if($should_disable) {
                 $disabled_job_platform =    DisabledJobPlatform::where([
                     'job_platform_id' => $job_platform->id,
                     'business_id' => auth()->user()->business_id,
                     'created_by' => auth()->user()->id,
                 ])->first();
                 if(!$disabled_job_platform) {
                    DisabledJobPlatform::create([
                         'job_platform_id' => $job_platform->id,
                         'business_id' => auth()->user()->business_id,
                         'created_by' => auth()->user()->id,
                     ]);
                 } else {
                     $disabled_job_platform->delete();
                 }
             }


             return response()->json(['message' => 'Job platform status updated successfully'], 200);
         } catch (Exception $e) {
             error_log($e->getMessage());
             return $this->sendError($e, 500, $request);
         }
     }
        /**
     *
     * @OA\Get(
     *      path="/v1.0/job-platforms",
     *      operationId="getJobPlatforms",
     *      tags={"job_platforms"},
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
     *   *    *      * *  @OA\Parameter(
     * name="job_listing_id",
     * in="query",
     * description="job_listing_id",
     * required=true,
     * example="1"
     * ),


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
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get job platforms  ",
     *      description="This method is to get job platforms ",
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

    public function getJobPlatforms(Request $request)
    {
        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");
            if (!$request->user()->hasPermissionTo('job_platform_view')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }
            $created_by  = NULL;
            if(auth()->user()->business) {
                $created_by = auth()->user()->business->created_by;
            }

            $job_platforms = JobPlatform::when(empty($request->user()->business_id), function ($query) use ($request, $created_by) {
                if (auth()->user()->hasRole('superadmin')) {
                    return $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 1)
                        ->when(isset($request->is_active), function ($query) use ($request) {
                            return $query->where('job_platforms.is_active', intval($request->is_active));
                        });
                } else {
                    return $query

                    ->where(function($query) use($request) {
                        $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 1)
                        ->where('job_platforms.is_active', 1)
                        ->when(isset($request->is_active), function ($query) use ($request) {
                            if(intval($request->is_active)) {
                                return $query->whereDoesntHave("disabled", function($q) {
                                    $q->whereIn("disabled_job_platforms.created_by", [auth()->user()->id]);
                                });
                            }

                        })
                        ->orWhere(function ($query) use ($request) {
                            $query->where('job_platforms.business_id', NULL)
                                ->where('job_platforms.is_default', 0)
                                ->where('job_platforms.created_by', auth()->user()->id)
                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    return $query->where('job_platforms.is_active', intval($request->is_active));
                                });
                        });

                    });
                }
            })
                ->when(!empty($request->user()->business_id), function ($query) use ($request, $created_by) {
                    return $query
                    ->where(function($query) use($request, $created_by) {


                        $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 1)
                        ->where('job_platforms.is_active', 1)
                        ->whereDoesntHave("disabled", function($q) use($created_by) {
                            $q->whereIn("disabled_job_platforms.created_by", [$created_by]);
                        })
                        ->when(isset($request->is_active), function ($query) use ($request, $created_by)  {
                            if(intval($request->is_active)) {
                                return $query->whereDoesntHave("disabled", function($q) use($created_by) {
                                    $q->whereIn("disabled_job_platforms.business_id",[auth()->user()->business_id]);
                                });
                            }

                        })


                        ->orWhere(function ($query) use($request, $created_by){
                            $query->where('job_platforms.business_id', NULL)
                                ->where('job_platforms.is_default', 0)
                                ->where('job_platforms.created_by', $created_by)
                                ->where('job_platforms.is_active', 1)

                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    if(intval($request->is_active)) {
                                        return $query->whereDoesntHave("disabled", function($q) {
                                            $q->whereIn("disabled_job_platforms.business_id",[auth()->user()->business_id]);
                                        });
                                    }

                                })


                                ;
                        })
                        ->orWhere(function ($query) use($request) {
                            $query->where('job_platforms.business_id', auth()->user()->business_id)
                                ->where('job_platforms.is_default', 0)
                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    return $query->where('job_platforms.is_active', intval($request->is_active));
                                });;
                        });
                    });


                })


                ->when(!empty($request->search_key), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $term = $request->search_key;
                    $query->where("job_platforms.name", "like", "%" . $term . "%")
                        ->orWhere("job_platforms.description", "like", "%" . $term . "%");
                });
            })

            //     when($request->user()->hasRole('superadmin'), function ($query) use ($request) {
            //     return $query->where('job_platforms.business_id', NULL)
            //                  ->where('job_platforms.is_default', 1);
            // })
            // ->when(!$request->user()->hasRole('superadmin'), function ($query) use ($request) {
            //     return $query->where('job_platforms.business_id', $request->user()->business_id);
            // })


                //    ->when(!empty($request->product_category_id), function ($query) use ($request) {
                //        return $query->where('product_category_id', $request->product_category_id);
                //    })
                ->when(!empty($request->job_listing_id), function ($query) use ($request) {
                    return $query->whereHas('job_listings',function($query) use($request) {
                        $query->where('job_listings.id',$request->job_listing_id);
                    });
                })
                ->when(!empty($request->start_date), function ($query) use ($request) {
                    return $query->where('job_platforms.created_at', ">=", $request->start_date);
                })
                ->when(!empty($request->end_date), function ($query) use ($request) {
                    return $query->where('job_platforms.created_at', "<=", ($request->end_date . ' 23:59:59'));
                })
                ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                    return $query->orderBy("job_platforms.id", $request->order_by);
                }, function ($query) {
                    return $query->orderBy("job_platforms.id", "DESC");
                })
                ->when(!empty($request->per_page), function ($query) use ($request) {
                    return $query->paginate($request->per_page);
                }, function ($query) {
                    return $query->get();
                });;



            return response()->json($job_platforms, 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }
    /**
     *
     * @OA\Get(
     *      path="/v1.0/client/job-platforms",
     *      operationId="getJobPlatformsClient",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },

        *              @OA\Parameter(
     *         name="business_id",
     *         in="query",
     *         description="business_id",
     *         required=true,
     *  example="6"
     *      ),
     *              @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="per_page",
     *         required=true,
     *  example="6"
     *      ),
     *   *    *      * *  @OA\Parameter(
     * name="job_listing_id",
     * in="query",
     * description="job_listing_id",
     * required=true,
     * example="1"
     * ),


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
     * *  @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="order_by",
     * required=true,
     * example="ASC"
     * ),

     *      summary="This method is to get job platforms  ",
     *      description="This method is to get job platforms ",
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

    public function getJobPlatformsClient(Request $request)
    {
        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");

            $business_id =  $request->business_id;
            if(!$business_id) {
               $error = [ "message" => "The given data was invalid.",
               "errors" => ["business_id"=>["The business id field is required."]]
               ];
                   throw new Exception(json_encode($error),422);
            }

            $business = Business::where([
                "id" => $business_id
            ])
            ->first();

           $created_by = $business->created_by;

            $job_platforms = JobPlatform::

           where(function($query) use($request, $created_by, $business_id) {


                        $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 1)
                        ->where('job_platforms.is_active', 1)
                        ->whereDoesntHave("disabled", function($q) use($created_by) {
                            $q->whereIn("disabled_job_platforms.created_by", [$created_by]);
                        })

                        ->whereDoesntHave("disabled", function($q) use($business_id)  {
                                    $q->whereIn("disabled_job_platforms.business_id",[$business_id]);
                        })


                        ->orWhere(function ($query) use($request, $created_by, $business_id){
                            $query->where('job_platforms.business_id', NULL)
                                ->where('job_platforms.is_default', 0)
                                ->where('job_platforms.created_by', $created_by)
                                ->where('job_platforms.is_active', 1)
                                ->whereDoesntHave("disabled", function($q) use($business_id){
                                            $q->whereIn("disabled_job_platforms.business_id",[$business_id]);
                                });
                        })
                        ->orWhere(function ($query) use($request, $business_id) {
                            $query->where('job_platforms.business_id', $business_id)
                                ->where('job_platforms.is_default', 0)
                                ->where('job_platforms.is_active', intval($request->is_active));
                        });
                    })





                ->when(!empty($request->search_key), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $term = $request->search_key;
                    $query->where("job_platforms.name", "like", "%" . $term . "%")
                        ->orWhere("job_platforms.description", "like", "%" . $term . "%");
                });
            })


                ->when(!empty($request->job_listing_id), function ($query) use ($request) {
                    return $query->whereHas('job_listings',function($query) use($request) {
                        $query->where('job_listings.id',$request->job_listing_id);
                    });
                })
                ->when(!empty($request->start_date), function ($query) use ($request) {
                    return $query->where('job_platforms.created_at', ">=", $request->start_date);
                })
                ->when(!empty($request->end_date), function ($query) use ($request) {
                    return $query->where('job_platforms.created_at', "<=", ($request->end_date . ' 23:59:59'));
                })
                ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                    return $query->orderBy("job_platforms.id", $request->order_by);
                }, function ($query) {
                    return $query->orderBy("job_platforms.id", "DESC");
                })
                ->when(!empty($request->per_page), function ($query) use ($request) {
                    return $query->paginate($request->per_page);
                }, function ($query) {
                    return $query->get();
                });



            return response()->json($job_platforms, 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/v1.0/job-platforms/{id}",
     *      operationId="getJobPlatformById",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *              @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *  example="6"
     *      ),
     *      summary="This method is to get job platform by id",
     *      description="This method is to get job platform by id",
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


    public function getJobPlatformById($id, Request $request)
    {
        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");
            if (!$request->user()->hasPermissionTo('job_platform_view')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }

            $job_platform =  JobPlatform::where([
                "id" => $id,
            ])
            // ->when($request->user()->hasRole('superadmin'), function ($query) use ($request) {
            //     return $query->where('job_platforms.business_id', NULL)
            //                  ->where('job_platforms.is_default', 1);
            // })
            // ->when(!$request->user()->hasRole('superadmin'), function ($query) use ($request) {
            //     return $query->where('job_platforms.business_id', $request->user()->business_id);
            // })
                ->first();
            if (!$job_platform) {

                return response()->json([
                    "message" => "no data found"
                ], 404);
            }

            if (empty(auth()->user()->business_id)) {

                if (auth()->user()->hasRole('superadmin')) {
                    if (($job_platform->business_id != NULL || $job_platform->is_default != 1)) {

                        return response()->json([
                            "message" => "You do not have permission to update this job platform due to role restrictions."
                        ], 403);
                    }
                } else {
                    if ($job_platform->business_id != NULL) {

                        return response()->json([
                            "message" => "You do not have permission to update this job platform due to role restrictions."
                        ], 403);
                    } else if ($job_platform->is_default == 0 && $job_platform->created_by != auth()->user()->id) {

                            return response()->json([
                                "message" => "You do not have permission to update this job platform due to role restrictions."
                            ], 403);

                    }
                }
            } else {
                if ($job_platform->business_id != NULL) {
                    if (($job_platform->business_id != auth()->user()->business_id)) {

                        return response()->json([
                            "message" => "You do not have permission to update this job platform due to role restrictions."
                        ], 403);
                    }
                } else {
                    if ($job_platform->is_default == 0) {
                        if ($job_platform->created_by != auth()->user()->created_by) {

                            return response()->json([
                                "message" => "You do not have permission to update this job platform due to role restrictions."
                            ], 403);
                        }
                    }
                }
            }

            return response()->json($job_platform, 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }


    /**
     *
     *     @OA\Delete(
     *      path="/v1.0/job-platforms/{ids}",
     *      operationId="deleteJobPlatformsByIds",
     *      tags={"job_platforms"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *              @OA\Parameter(
     *         name="ids",
     *         in="path",
     *         description="ids",
     *         required=true,
     *  example="1,2,3"
     *      ),
     *      summary="This method is to delete job platform by id",
     *      description="This method is to delete job platform by id",
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

    public function deleteJobPlatformsByIds(Request $request, $ids)
    {

        try {
            $this->storeActivity($request, "DUMMY activity","DUMMY description");
            if (!$request->user()->hasPermissionTo('job_platform_delete')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }

            $idsArray = explode(',', $ids);
            $existingIds = JobPlatform::whereIn('id', $idsArray)
            ->when(empty($request->user()->business_id), function ($query) use ($request) {
                if ($request->user()->hasRole("superadmin")) {
                    return $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 1);
                } else {
                    return $query->where('job_platforms.business_id', NULL)
                        ->where('job_platforms.is_default', 0)
                        ->where('job_platforms.created_by', $request->user()->id);
                }
            })
            ->when(!empty($request->user()->business_id), function ($query) use ($request) {
                return $query->where('job_platforms.business_id', $request->user()->business_id)
                    ->where('job_platforms.is_default', 0);
            })
                ->select('id')
                ->get()
                ->pluck('id')
                ->toArray();
            $nonExistingIds = array_diff($idsArray, $existingIds);

            if (!empty($nonExistingIds)) {

                return response()->json([
                    "message" => "Some or all of the specified data do not exist."
                ], 404);
            }

            $job_post_exists =  JobListingJobPlatforms::whereIn("job_platform_id", $existingIds)->exists();
            if ($job_post_exists) {

                return response()->json([
                    "message" => "Some user's are using some of these job platforms.",
                    // "conflicting_users" => $conflictingSocialSites
                ], 409);
            }

            JobPlatform::destroy($existingIds);


            return response()->json(["message" => "data deleted sussfully","deleted_ids" => $existingIds], 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }
}
