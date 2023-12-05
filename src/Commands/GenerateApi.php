<?php

namespace lararest\RestApiGenerator\Commands;

use lararest\RestApiGenerator\RestApiGenerator;
use Illuminate\Console\Command;

class GenerateApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:gen {--model=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'REST Api Generator With API Resources';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($this->option('model'))) {
            $this->error('Model Name Argument not found!');

            return false;
        }

        if (!file_exists(base_path(config('restapi-generator.model_directory_path') . '/' . $this->option('model') . '.php'))) {
            $this->error('Model does not exist!');

            return false;
        }

        $api = new RestApiGenerator($this->option('model'));

        $collectionHelper = $api->generateCollectionHelper();
        if ($collectionHelper) {
            $this->info('collectionHelper Generated SuccessFully!');
        } else {
            $this->error('collectionHelper Already Exists!');
        }

        $apiGeneralResponseTrait = $api->generateTrait();
        if ($apiGeneralResponseTrait) {
            $this->info('ApiGeneralResponse Trait Generated SuccessFully!');
        } else {
            $this->error('ApiGeneralResponse Trait Already Exists!');
        }

        $getRequest = $api->generateGetRequest();
        if ($getRequest) {
            $this->info('Get Request Generated SuccessFully!');
        } else {
            $this->error('Get Request Already Exists!');
        }

        $storeRequest = $api->generateStoreRequest();
        if ($storeRequest) {
            $this->info('Store Request Generated SuccessFully!');
        } else {
            $this->error('Store Request Already Exists!');
        }

        $updateRequest = $api->generateUpdateRequest();
        if ($updateRequest) {
            $this->info('Update Request Generated SuccessFully!');
        } else {
            $this->error('Update Request Already Exists!');
        }

        $deleteRequest = $api->generateDeleteRequest();
        if ($deleteRequest) {
            $this->info('Delete Request Generated SuccessFully!');
        } else {
            $this->error('Delete Request Already Exists!');
        }

        $controller = $api->generateController();
        if ($controller) {
            $this->info('Controller Generated SuccessFully!');
        } else {
            $this->error('Controller Already Exists!');
        }

        $resource = $api->generateResource();
        if ($resource) {
            $this->info('Resource Generated SuccessFully!');
        } else {
            $this->error('Resource Already Exists!');
        }

        $collection = $api->generateCollection();
        if ($collection) {
            $this->info('Collection Generated SuccessFully!');
        } else {
            $this->error('Collection Already Exists!');
        }

        $route = $api->generateRoute();
        if ($route) {
            $this->info('Route Generated SuccessFully!');
        } else {
            $this->error('Route Already Exists!');
        }

        $this->info('Api Created SuccessFully!');

        return true;
    }
}
