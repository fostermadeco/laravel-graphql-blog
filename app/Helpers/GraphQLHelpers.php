<?php

/**
 * @param $query
 * @return mixed
 */
function graphql($query, $variables = null)
{
    return app('graphql')->query($query, $variables);
}

function graphql_json($query, $variables = null)
{
    return json_decode(json_encode(app('graphql')->query($query, $variables)));
}

function graphql_schema()
{
    $introspection_query = "
  query IntrospectionQuery {
    __schema {
      queryType { name }
      mutationType { name }
      subscriptionType { name }
      types {
        ...FullType
      }
      directives {
        name
        description
        locations
        args {
          ...InputValue
        }
      }
    }
  }

  fragment FullType on __Type {
    kind
    name
    description
    fields(includeDeprecated: true) {
      name
      description
      args {
        ...InputValue
      }
      type {
        ...TypeRef
      }
      isDeprecated
      deprecationReason
    }
    inputFields {
      ...InputValue
    }
    interfaces {
      ...TypeRef
    }
    enumValues(includeDeprecated: true) {
      name
      description
      isDeprecated
      deprecationReason
    }
    possibleTypes {
      ...TypeRef
    }
  }

  fragment InputValue on __InputValue {
    name
    description
    type { ...TypeRef }
    defaultValue
  }

  fragment TypeRef on __Type {
    kind
    name
    ofType {
      kind
      name
      ofType {
        kind
        name
        ofType {
          kind
          name
          ofType {
            kind
            name
            ofType {
              kind
              name
              ofType {
                kind
                name
                ofType {
                  kind
                  name
                }
              }
            }
          }
        }
      }
    }
  }
";
    return graphql($introspection_query);
}

/**
 * @param bool $force_update
 */
function generate_graphql_types($force_update = false)
{
    $files = \File::allFiles(app_path('Models'));
    if (count($files)) {
        foreach ($files as $file) {
            if ($file->getRelativePath() =="") {
                $info = pathinfo($file);
                $model_name = str_replace(".php", "", $info["basename"]);
                $query_name = str_replace("Type", "type", $model_name);

                $params = ['type'=>'graphql:type',
                            'name' => $query_name."Type",
                            '--default_fields' => true,
                            '--model' => $model_name];
                if ($force_update) {
                    $params['--force'] = true;
                }

                try {
                    \Artisan::call('generate', $params);
                } catch (\Exception $e) {
                    \Log::info($e->getMessage());
                }
            }
        }
    }
}

/**
 * @param bool $force_update
 */
function generate_graphql_queries($force_update = false)
{
    $types = list_graphql_types();
    if (count($types)) {
        foreach ($types as $type_name => $type) {
            $name = studly_case($type_name);
            $queries = [ $name , str_plural($name)];
            foreach ($queries as $query) {
                $params = ['type'=>'graphql:query',
                           'name' => $query."Query",
                           '--model' => $name];

                if ($query == str_plural($query)) {
                    $params['--collection'] = true;
                }

                if ($force_update) {
                    $params['--force'] = true;
                }

                try {
                    \Artisan::call('generate', $params);
                } catch (\Exception $e) {
                    \Log::info($e->getMessage());
                }
            }
        }
    }
}

/**
 * @param bool $force_update
 */
function generate_graphql_mutations($force_update = false)
{
    $types = list_graphql_types();
    if (count($types)) {
        foreach ($types as $type_name => $type) {
            $name = studly_case($type_name);
            $operations = [ 'create','update','delete'];
            foreach ($operations as $operation) {
                $params = ['type'=>'graphql:mutation',
                            'name' => ucwords($operation).$name."Mutation",
                            '--model' => $name,
                            '--operation'=>$operation,
                            '--default_fields'=>true];

                if ($force_update) {
                    $params['--force'] = true;
                }

                try {
                    \Artisan::call('generate', $params);
                } catch (\Exception $e) {
                    \Log::info($e->getMessage());
                }
            }
        }
    }
}

/**
 * @param int $depth
 * @param bool $force_update
 */
function generate_graphql_js_queries_and_mutations($depth = 1, $force_update = false)
{
    $files = \File::allFiles(app_path('Models'));

    if (count($files)) {
        foreach ($files as $file) {
            if ($file->getRelativePath() == "") {
                $info = pathinfo($file);
                $model_name = str_replace(".php", "", $info["basename"]);

                if (!in_array($model_name, ["Model"])) {
                    $file_name = snake_case(str_plural($model_name));

                    $params = ['type'=>'graphql:js-queries-and-mutations',
                                'name' => $file_name,
                                '--depth' => $depth,
                                '--model' => $model_name];

                    if ($force_update) {
                        $params['--force'] = true;
                    }

                    try {
                        \Artisan::call('generate', $params);
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }
}


/**
 * @return array
 */
function list_graphql_types()
{
    $file_paths = \File::allFiles(app_path('GraphQL/Types'));
    $types = [];

    if (count($file_paths)) {
        foreach ($file_paths as $file_path) {
            $info = pathinfo($file_path);
            $class_name = str_replace(".php", "", $info["basename"]);
            $type_name = ucwords(camel_case(str_replace(["Type","type"], ["","_type"], $class_name)));
            $types[$type_name] = "App\GraphQL\Types\\".$class_name;
        }
    }

    return $types;
}

/**
 * @return array
 */
function list_graphql_queries()
{
    $file_paths = \File::allFiles(app_path('GraphQL/Queries'));
    $queries = [];

    if (count($file_paths)) {
        foreach ($file_paths as $file_path) {
            $info = pathinfo($file_path);
            $class_name = str_replace(".php", "", $info["basename"]);
            $query_name = snake_case(str_replace(["Query"], [""], $class_name));
            $queries[$query_name] = "App\GraphQL\Queries\\".$class_name;
        }
    }

    return $queries;
}

/**
 * @return array
 */
function list_graphql_mutations()
{
    $file_paths = \File::allFiles(app_path('GraphQL/Mutations'));
    $mutations = [];

    if (count($file_paths)) {
        foreach ($file_paths as $file_path) {
            $info = pathinfo($file_path);
            $class_name = str_replace(".php", "", $info["basename"]);
            $mutation_name = camel_case(str_replace(["Mutation"], [""], $class_name));
            $mutations[$mutation_name] = "App\GraphQL\Mutations\\".$class_name;
        }
    }

    return $mutations;
}

/**
 * @param $type
 * @return array
 */
function compute_graphql_type_fields_for_php($type)
{
    $type_class = "App\GraphQL\Types\\{$type}Type";
    $type_entity_fields = (new $type_class)->fields();
    $fields = [];
    foreach ($type_entity_fields as $key => $data) {
        $field_type = "";


        if (in_array(class_basename($data['type']), ['NonNull','ListOfType'])) {
            if (in_array($data['type']->getWrappedType()->name, ["String","Boolean","Int","Float"])) {
                $field_type = "Type::".strtolower($data['type']->getWrappedType()->name)."()";
            } else {
                $field_type = "GraphQL::type('".$data['type']->getWrappedType()->name."')";
            }
            $fields[$key] = [
                                "type" => "Type::".camel_case(class_basename($data['type']))."(".$field_type.")" ,
                                "description" => "The ".str_replace("_", " ", $key)." of the ".str_replace("_", " ", snake_case($type))
                              ];
        } else {
            if (in_array($data['type']->name, ["String","Boolean","Int","Float"])) {
                $field_type = "Type::".strtolower($data['type']->name)."()";
            } else {
                $field_type = "GraphQL::type('".$data['type']->name."')";
            }

            $fields[$key] = [
                                "type" =>$field_type,
                                "description" => "The ".str_replace("_", " ", $key)." of the ".str_replace("_", " ", snake_case($type))
                            ];
        }
    }

    return $fields;
}


/**
 * @param $type
 * @param int $depth
 * @return array
 */
function compute_graphql_type_fields_for_javascript($type, $depth = 0)
{
    $type_class = "App\GraphQL\Types\\{$type}Type";
    $type_entity_fields = (new $type_class)->fields();
    $fields = [];
    foreach ($type_entity_fields as $key => $data) {
        $fields[$key] = (in_array(class_basename($data['type']), ['NonNull','ListOfType'])) ? $data['type']->getWrappedType()->name : $data['type']->name;

        if (in_array($fields[$key], ["String","Boolean","Int","Float"])) {
            $fields[$key] = "";
        } else {
            if ($depth > 0) {
                $fields[$key] = compute_graphql_type_fields_for_javascript($fields[$key], $depth - 1);
            } else {
                unset($fields[$key]);
            }
        }
    }
    return $fields;
}

/**
 * @param $model
 * @param int $depth
 * @return array
 */
function compute_graphql_model_data_for_javascript($model, $depth = 2)
{
    $query_function_name_singular = camel_case($model);
    $query_function_name_plural = camel_case(str_plural($model));
    $query_name_singular = snake_case($model);
    $query_name_plural = str_plural($query_name_singular);
    $mutation_name = ucwords($query_function_name_singular);
    $type_name = ucwords(camel_case(str_replace(["Type","type"], ["","_type"], $model)));

    $type_class = "App\GraphQL\Types\\{$type_name}Type";

    $query_classes = ["App\GraphQL\Queries\\{$query_function_name_singular}Query",
                      "App\GraphQL\Queries\\{$query_function_name_plural}Query"];

    $mutation_classes = ["App\GraphQL\Mutations\create{$mutation_name}Mutation",
                         "App\GraphQL\Mutations\update{$mutation_name}Mutation",
                         "App\GraphQL\Mutations\delete{$mutation_name}Mutation"];

    $model_class = "App\Models\\{$model}";
    $model_entity = new $model_class;

    $fields =  str_replace(['"',':',','], "", json_encode(compute_graphql_type_fields_for_javascript($model, $depth), JSON_PRETTY_PRINT));

    foreach (array_merge($query_classes, $mutation_classes) as $query_key => $query_class) {
        if (class_exists($query_class)) {
            $args = [];
            $query = new $query_class;

            if (count($query->args())) {
                foreach ($query->args() as $key => $arg) {
                    if (isset($arg["type"])) {
                        $field_type = $arg["type"];
                        $rules = $query->rules();
                        $required = (isset($rules[$key]) && !is_null($rules[$key]) && in_array('required', $rules[$key]) && !in_array('sometimes', $rules[$key])) ? '!' : '';

                        if (in_array(class_basename($field_type), [ "NonNull", "ListOfType"])) {
                            $type = (class_basename($field_type) == "ListOfType") ? "[".$field_type->getWrappedType()->name."]" : $field_type->getWrappedType()->name;
                            $args[] = ["name"=>$key,
                                       "type"=>$type.$required];
                        } elseif (!isset($field_type->config)) {
                            $args[] = ["name"=>$key,
                                       "type"=>$field_type->name.$required];
                        }
                    }
                }
            }

            $data[] =    [
                            "query_function_name" => class_basename($query_class),
                            "query_name" => ($query_key == 1) ? $query_name_plural : $query_name_singular,
                            "query_type" => in_array($query_class, $query_classes) ? "query" : "mutation",
                            "args" => $args,
                            "fields" => $fields
                        ];
        }
    }

    return $data;
}

/**
 * @param $model
 * @param bool $force_update
 */
function generate_vue_templates($model, $force_update = false)
{
    $model = studly_case($model);
    $model = ($model == str_plural($model)) ? str_singular($model) : $model;

    $folder = strtolower(str_plural($model));

    $actions = ["all","create","edit"];

    foreach ($actions as $action) {
        $file_name = ($action == "index") ? "all".str_plural($model) : $action.$model;

        $params = ['type'=>'vue:template',
                    'name' => $folder."/".$file_name,
                    'action' => $action,
                    '--model' => $model];

        if ($force_update) {
            $params['--force'] = true;
        }

        try {
            \Artisan::call('generate', $params);
        } catch (\Exception $e) {
        }
    }
}
