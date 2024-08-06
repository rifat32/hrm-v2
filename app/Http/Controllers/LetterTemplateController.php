<?php





namespace App\Http\Controllers;

use App\Http\Requests\LetterTemplateCreateRequest;
use App\Http\Requests\LetterTemplateUpdateRequest;
use App\Http\Requests\GetIdRequest;
use App\Http\Utils\BasicUtil;
use App\Http\Utils\BusinessUtil;
use App\Http\Utils\ErrorUtil;
use App\Http\Utils\UserActivityUtil;
use App\Models\LetterTemplate;
use App\Models\DisabledLetterTemplate;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LetterTemplateController extends Controller
{
    use ErrorUtil, UserActivityUtil, BusinessUtil, BasicUtil;
    /**
     *
     * @OA\Post(
     *      path="/v1.0/letter-templates",
     *      operationId="createLetterTemplate",
     *      tags={"letter_templates"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to store letter templates",
     *      description="This method is to store letter templates",
     *
     *  @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     * @OA\Property(property="name", type="string", format="string", example="tttttt"),
     * @OA\Property(property="description", type="string", format="string", example="erg ear ga "),
     * @OA\Property(property="template", type="string", format="string", example="template"),
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

    public function createLetterTemplate(LetterTemplateCreateRequest $request)
    {

        try {
            $this->storeActivity($request, "DUMMY activity", "DUMMY description");
            return DB::transaction(function () use ($request) {
                if (!$request->user()->hasPermissionTo('letter_template_create')) {
                    return response()->json([
                        "message" => "You can not perform this action"
                    ], 401);
                }

                if(!empty(auth()->user()->business_id)) {
                    $letter_template_counts = LetterTemplate::where([
                        "business_id" => auth()->user()->business_id
                    ])
                    ->count();

                    if($letter_template_counts >= 20) {
                        throw new \Exception("You have exceeded the maximum number of letter templates allowed.", 401);
                    }
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



                // $request_data["template"] = json_encode($request_data["template"]);
                $letter_template =  LetterTemplate::create($request_data);




                return response($letter_template, 201);
            });
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }

    /**
     *
     * @OA\Put(
     *      path="/v1.0/letter-templates",
     *      operationId="updateLetterTemplate",
     *      tags={"letter_templates"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to update letter templates ",
     *      description="This method is to update letter templates ",
     *
     *  @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *      @OA\Property(property="id", type="number", format="number", example="1"),
     * @OA\Property(property="name", type="string", format="string", example="tttttt"),
     * @OA\Property(property="description", type="string", format="string", example="erg ear ga "),
     *      * @OA\Property(property="template", type="string", format="string", example="template")

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

    public function updateLetterTemplate(LetterTemplateUpdateRequest $request)
    {

        try {
            $this->storeActivity($request, "DUMMY activity", "DUMMY description");
            return DB::transaction(function () use ($request) {
                if (!$request->user()->hasPermissionTo('letter_template_update')) {
                    return response()->json([
                        "message" => "You can not perform this action"
                    ], 401);
                }
                $request_data = $request->validated();



                $letter_template_query_params = [
                    "id" => $request_data["id"],
                ];

                $letter_template = LetterTemplate::where($letter_template_query_params)->first();

if ($letter_template) {
    // $request_data["template"] = json_encode($request_data["template"]);
$letter_template->fill(collect($request_data)->only([
'name',
'description',
'template'
// "is_default",
// "is_active",
// "business_id",
// "created_by"
])->toArray());
$letter_template->save();
} else {
    return response()->json([
                        "message" => "something went wrong."
                    ], 500);
                }




                return response($letter_template, 201);
            });
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $this->sendError($e, 500, $request);
        }
    }
    /**
     *
     * @OA\Put(
     *      path="/v1.0/letter-templates/toggle-active",
     *      operationId="toggleActiveLetterTemplate",
     *      tags={"letter_templates"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *      summary="This method is to toggle letter templates",
     *      description="This method is to toggle letter templates",
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

    public function toggleActiveLetterTemplate(GetIdRequest $request)
    {

        try {
            $this->storeActivity($request, "DUMMY activity", "DUMMY description");
            if (!$request->user()->hasPermissionTo('letter_template_activate')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }
            $request_data = $request->validated();

            $letter_template =  LetterTemplate::where([
                "id" => $request_data["id"],
            ])
                ->first();
            if (!$letter_template) {

                return response()->json([
                    "message" => "no data found"
                ], 404);
            }









            $should_update = 0;
            $should_disable = 0;
            if (empty(auth()->user()->business_id)) {

                if (auth()->user()->hasRole('superadmin')) {
                    if (($letter_template->business_id != NULL || $letter_template->is_default != 1)) {

                        return response()->json([
                            "message" => "You do not have permission to update this letter template due to role restrictions."
                        ], 403);
                    } else {
                        $should_update = 1;
                    }
                } else {
                    if ($letter_template->business_id != NULL) {

                        return response()->json([
                            "message" => "You do not have permission to update this letter template due to role restrictions."
                        ], 403);
                    } else if ($letter_template->is_default == 0) {

                        if($letter_template->created_by != auth()->user()->id) {

                            return response()->json([
                                "message" => "You do not have permission to update this letter template due to role restrictions."
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
                if ($letter_template->business_id != NULL) {
                    if (($letter_template->business_id != auth()->user()->business_id)) {

                        return response()->json([
                            "message" => "You do not have permission to update this letter template due to role restrictions."
                        ], 403);
                    } else {
                        $should_update = 1;
                    }
                } else {
                    if ($letter_template->is_default == 0) {
                        if ($letter_template->created_by != auth()->user()->created_by) {

                            return response()->json([
                                "message" => "You do not have permission to update this letter template due to role restrictions."
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
                $letter_template->update([
                    'is_active' => !$letter_template->is_active
                ]);
            }

            if($should_disable) {
                $disabled_letter_template =    DisabledLetterTemplate::where([
                    'letter_template_id' => $letter_template->id,
                    'business_id' => auth()->user()->business_id,
                    'created_by' => auth()->user()->id,
                ])->first();
                if(!$disabled_letter_template) {
                    DisabledLetterTemplate::create([
                        'letter_template_id' => $letter_template->id,
                        'business_id' => auth()->user()->business_id,
                        'created_by' => auth()->user()->id,
                    ]);
                } else {
                    $disabled_letter_template->delete();
                }
            }


            return response()->json(['message' => 'letter template status updated successfully'], 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $this->sendError($e, 500, $request);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/v1.0/letter-templates",
     *      operationId="getLetterTemplates",
     *      tags={"letter_templates"},
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
     * name="is_active",
     * in="query",
     * description="is_active",
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
     * *  @OA\Parameter(
      * name="id",
      * in="query",
      * description="id",
      * required=true,
      * example="ASC"
      * ),
      * *  @OA\Parameter(
        * name="is_single_search",
        * in="query",
        * description="is_single_search",
        * required=true,
        * example="ASC"
        * ),




     *      summary="This method is to get letter templates  ",
     *      description="This method is to get letter templates ",
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

    public function getLetterTemplates(Request $request)
    {
        try {
            $this->storeActivity($request, "DUMMY activity", "DUMMY description");
            if (!$request->user()->hasPermissionTo('letter_template_view')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }
            $created_by  = NULL;
            if(auth()->user()->business) {
                $created_by = auth()->user()->business->created_by;
            }



            $letter_templates = LetterTemplate::when(empty($request->user()->business_id), function ($query) use ($request, $created_by) {
                if (auth()->user()->hasRole('superadmin')) {
                    return $query->where('letter_templates.business_id', NULL)
                        ->where('letter_templates.is_default', 1)
                        ->when(isset($request->is_active), function ($query) use ($request) {
                            return $query->where('letter_templates.is_active', intval($request->is_active));
                        });
                } else {
                    return $query

                    ->where(function($query) use($request) {
                        $query->where('letter_templates.business_id', NULL)
                        ->where('letter_templates.is_default', 1)
                        ->where('letter_templates.is_active', 1)
                        ->when(isset($request->is_active), function ($query) use ($request) {
                            if(intval($request->is_active)) {
                                return $query->whereDoesntHave("disabled", function($q) {
                                    $q->whereIn("disabled_letter_templates.created_by", [auth()->user()->id]);
                                });
                            }

                        })
                        ->orWhere(function ($query) use ($request) {
                            $query->where('letter_templates.business_id', NULL)
                                ->where('letter_templates.is_default', 0)
                                ->where('letter_templates.created_by', auth()->user()->id)
                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    return $query->where('letter_templates.is_active', intval($request->is_active));
                                });
                        });

                    });
                }
            })
                ->when(!empty($request->user()->business_id), function ($query) use ($request, $created_by) {
                    return $query
                    ->where(function($query) use($request, $created_by) {


                        $query->where('letter_templates.business_id', NULL)
                        ->where('letter_templates.is_default', 1)
                        ->where('letter_templates.is_active', 1)
                        ->whereDoesntHave("disabled", function($q) use($created_by) {
                            $q->whereIn("disabled_letter_templates.created_by", [$created_by]);
                        })
                        ->when(isset($request->is_active), function ($query) use ($request, $created_by)  {
                            if(intval($request->is_active)) {
                                return $query->whereDoesntHave("disabled", function($q) use($created_by) {
                                    $q->whereIn("disabled_letter_templates.business_id",[auth()->user()->business_id]);
                                });
                            }

                        })


                        ->orWhere(function ($query) use($request, $created_by){
                            $query->where('letter_templates.business_id', NULL)
                                ->where('letter_templates.is_default', 0)
                                ->where('letter_templates.created_by', $created_by)
                                ->where('letter_templates.is_active', 1)

                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    if(intval($request->is_active)) {
                                        return $query->whereDoesntHave("disabled", function($q) {
                                            $q->whereIn("disabled_letter_templates.business_id",[auth()->user()->business_id]);
                                        });
                                    }

                                })


                                ;
                        })
                        ->orWhere(function ($query) use($request) {
                            $query->where('letter_templates.business_id', auth()->user()->business_id)
                                ->where('letter_templates.is_default', 0)
                                ->when(isset($request->is_active), function ($query) use ($request) {
                                    return $query->where('letter_templates.is_active', intval($request->is_active));
                                });
                        });
                    });

                })
                ->when(!empty($request->id), function ($query) use ($request) {
                  return $query->where('letter_templates.id', $request->id);
              })
                ->when(!empty($request->search_key), function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        $term = $request->search_key;
                        $query->where("letter_templates.name", "like", "%" . $term . "%")
                            ->orWhere("letter_templates.description", "like", "%" . $term . "%");
                    });
                })

                ->when(!empty($request->start_date), function ($query) use ($request) {
                    return $query->where('letter_templates.created_at', ">=", $request->start_date);
                })
                ->when(!empty($request->end_date), function ($query) use ($request) {
                    return $query->where('letter_templates.created_at', "<=", ($request->end_date . ' 23:59:59'));
                })
                ->when(!empty($request->order_by) && in_array(strtoupper($request->order_by), ['ASC', 'DESC']), function ($query) use ($request) {
                    return $query->orderBy("letter_templates.id", $request->order_by);
                }, function ($query) {
                    return $query->orderBy("letter_templates.id", "DESC");
                })
                ->when($request->filled("is_single_search") && $request->boolean("is_single_search"), function ($query) use ($request) {
                  return $query->first();
          }, function($query) {
             return $query->when(!empty(request()->per_page), function ($query) {
                  return $query->paginate(request()->per_page);
              }, function ($query) {
                  return $query->get();
              });
          });

          if($request->filled("is_single_search") && empty($letter_templates)){
     throw new Exception("No data found",404);
          }


            return response()->json($letter_templates, 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }

  /**
     *
     * @OA\Get(
     *      path="/v1.0/letter-template-variables",
     *      operationId="getLetterTemplateVariables",
     *      tags={"letter_templates"},
     *       security={
     *           {"bearerAuth": {}}
     *       },





     *      summary="This method is to get letter templates  ",
     *      description="This method is to get letter templates ",
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

     public function getLetterTemplateVariables(Request $request)
     {
         try {
             $this->storeActivity($request, "DUMMY activity", "DUMMY description");
             if (!$request->user()->hasPermissionTo('letter_template_view')) {
                 return response()->json([
                     "message" => "You can not perform this action"
                 ], 401);
             }

$letterTemplateVariables = $this->getLetterTemplateVariablesFunc();


            return response()->json($letterTemplateVariables, 200);

         } catch (Exception $e) {

             return $this->sendError($e, 500, $request);
         }
     }




    /**
     *
     *     @OA\Delete(
     *      path="/v1.0/letter-templates/{ids}",
     *      operationId="deleteLetterTemplatesByIds",
     *      tags={"letter_templates"},
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
     *      summary="This method is to delete letter template by id",
     *      description="This method is to delete letter template by id",
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

    public function deleteLetterTemplatesByIds(Request $request, $ids)
    {

        try {
            $this->storeActivity($request, "DUMMY activity", "DUMMY description");
            if (!$request->user()->hasPermissionTo('letter_template_delete')) {
                return response()->json([
                    "message" => "You can not perform this action"
                ], 401);
            }

            $idsArray = explode(',', $ids);
            $existingIds = LetterTemplate::whereIn('id', $idsArray)
                ->when(empty($request->user()->business_id), function ($query) use ($request) {
                    if ($request->user()->hasRole("superadmin")) {
                        return $query->where('letter_templates.business_id', NULL)
                            ->where('letter_templates.is_default', 1);
                    } else {
                        return $query->where('letter_templates.business_id', NULL)
                            ->where('letter_templates.is_default', 0)
                            ->where('letter_templates.created_by', $request->user()->id);
                    }
                })
                ->when(!empty($request->user()->business_id), function ($query) use ($request) {
                    return $query->where('letter_templates.business_id', $request->user()->business_id)
                        ->where('letter_templates.is_default', 0);
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






            LetterTemplate::destroy($existingIds);


            return response()->json(["message" => "data deleted sussfully", "deleted_ids" => $existingIds], 200);
        } catch (Exception $e) {

            return $this->sendError($e, 500, $request);
        }
    }
}







