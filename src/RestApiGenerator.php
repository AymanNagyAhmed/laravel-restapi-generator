<?php

namespace lararest\RestApiGenerator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class RestApiGenerator
{
    const STUB_DIR = __DIR__ . '/resources/stubs/';
    protected $model;
    protected $result = false;

    public function __construct(string $model)
    {
        $this->model = $model;
        self::generate();
    }

    public function generate()
    {
        self::directoryCreate();
    }

    public function directoryCreate()
    {
        // Helpers
        if (!file_exists(base_path('app/Helpers'))) {
            mkdir(base_path('app/Helpers'));
        }
        // Traits
        if (!file_exists(base_path('app/Traits'))) {
            mkdir(base_path('app/Traits'));
        }
        if (!file_exists(base_path('app/Traits/Api'))) {
            mkdir(base_path('app/Traits/Api'));
        }
        // Controllers
        if (!file_exists(base_path('app/Http/Controllers/Api'))) {
            mkdir(base_path('app/Http/Controllers/Api'));
        }
        // Resources
        if (!file_exists(base_path('app/Http/Resources'))) {
            mkdir(base_path('app/Http/Resources'));
        }
        if (!file_exists(base_path('app/Http/Requests/Api'))) {
            mkdir(base_path('app/Http/Requests/Api'));
        }
    }

    public function generateCollectionHelper(): bool
    {
        $this->result = false;
        if (!file_exists(base_path('app/Helpers/CollectionHelper.php'))) {
            $template = self::getStubContents('helpers/collection_helper.stub');
            file_put_contents(base_path('app/Helpers/CollectionHelper.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    public function generateTrait()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Traits/ApiGeneralResponse.php'))) {
            $template = self::getStubContents('traits/api_general_response.stub');
            file_put_contents(base_path('app/Traits/Api/ApiGeneralResponse.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    public function generateGetRequest()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Requests/Api/' . 'Get' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            // git columns from migrations
            // $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $columns = $model->getFillable();
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . ' => [],' . "\n \t\t\t";
            }
            $template = self::getStubContents('requests/get_request.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Api/' . 'Get' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    public function generateStoreRequest()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Requests/Api/' . 'Store' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getFillable();
            $print_columns = null;
            $columns_rules = $this->getColumnsRules($model->getTable(), $columns);
            foreach ($columns_rules as $column => $rules) {
                $print_columns .= "'" . $column . "'" . ' => ' . json_encode($rules) . ",\n \t\t\t";
            }
            $template = self::getStubContents('requests/store_request.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Api/' . 'Store' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    public function generateDeleteRequest()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Requests/Api/' . 'Delete' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getFillable();
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . ' => [],' . "\n \t\t\t";
            }
            $template = self::getStubContents('requests/delete_request.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Api/' . 'Delete' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    public function generateUpdateRequest()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Requests/Api/' . 'Update' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getFillable();
            $print_columns = null;
            $columns_rules = $this->getColumnsRules($model->getTable(), $columns);
            foreach ($columns_rules as $column => $rules) {
                $print_columns .= "'" . $column . "'" . ' => ' . json_encode($rules) . ",\n \t\t\t";
            }
            $template = self::getStubContents('requests/update_request.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Api/' . 'Update' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    public function generateController()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Controllers/Api/' . $this->model . 'Controller.php'))) {
            $template = self::getStubContents('controller.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{modelNameLower}}', strtolower($this->model), $template);
            $template = str_replace('{{modelNameCamel}}', Str::camel($this->model), $template);
            $template = str_replace('{{modelNameSpace}}', is_dir(base_path('app/Models')) ? 'Models\\' . $this->model : $this->model, $template);
            file_put_contents(base_path('app/Http/Controllers/Api/' . $this->model . 'Controller.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    public function generateResource()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Resources/' . $this->model . 'Resource.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . ' => $this->' . $column . ', ' . "\n \t\t\t";
            }
            $template = self::getStubContents('resource.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Resources/' . $this->model . 'Resource.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    public function generateCollection()
    {
        $this->result = false;
        if (!file_exists(base_path('app/Http/Resources/' . $this->model . 'Collection.php'))) {
            $template = self::getStubContents('collection.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            file_put_contents(base_path('app/Http/Resources/' . $this->model . 'Collection.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    public function generateRoute()
    {
        $this->result = false;
        if (floatval(app()->version()) >= 8) {
            $nameSpace = "\nuse App\Http\Controllers\Api\{{modelName}}Controller;";
            $template = "Route::prefix('{{modelNameLower}}')->group(function () {\n";
            $template .= "    Route::get('/', [{{modelName}}Controller::class, 'index']);\n";
            $template .= "    Route::post('/', [{{modelName}}Controller::class, 'store']);\n";
            $template .= "    Route::get('/{{{routeParam}}}', [{{modelName}}Controller::class, 'show']);\n";
            $template .= "    Route::put('/{{{routeParam}}}', [{{modelName}}Controller::class, 'update']);\n";
            $template .= "    Route::delete('/{{{routeParam}}}', [{{modelName}}Controller::class, 'destroy']);\n";
            $template .= "});\n";
            $nameSpace = str_replace('{{modelName}}', $this->model, $nameSpace);
        } else {
            $template = "Route::apiResource('{{modelNameLower}}', 'Api\{{modelName}}Controller');\n";
        }
        $route = str_replace('{{modelNameLower}}', Str::camel(Str::plural($this->model)), $template);
        $route = str_replace('{{modelName}}', $this->model, $route);
        $route = str_replace('{{routeParam}}', Str::camel(strtolower($this->model)), $route);

        if (!strpos(file_get_contents(base_path('routes/api.php')), $route)) {
            file_put_contents(base_path('routes/api.php'), $route, FILE_APPEND);
            if (floatval(app()->version()) >= 8) {
                if (!strpos(file_get_contents(base_path('routes/api.php')), $nameSpace)) {
                    $lines = file(base_path('routes/api.php'));
                    $lines[0] = $lines[0] . "\n" . $nameSpace;
                    file_put_contents(base_path('routes/api.php'), $lines);
                }
            }
            $this->result = true;
        }

        return $this->result;
    }

    private function getStubContents($stubName)
    {
        return file_get_contents(self::STUB_DIR . $stubName);
    }


    private function getColumnsRules($tableName, $columns)
    {
        $rules = [];
        foreach ($columns as $column) {
            $type = Schema::getColumnType($tableName, $column);

            switch ($type) {
                case 'char':
                    $rules[$column] = [];
                    break;
                case 'varchar':
                    $rules[$column] = ['string'];
                    break;
                case 'integer':
                    $rules[$column] = ['integer'];
                    break;
                case 'text':
                    $rules[$column] = ['text'];
                    break;
                case 'boolean':
                    $rules[$column] = ['boolean'];
                    break;
                case 'date':
                    $rules[$column] = ['date'];
                    break;

                default:
                    $rules[$column] = [];
            }
        }
        return $rules;
    }
}
