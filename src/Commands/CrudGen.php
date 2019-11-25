<?php

namespace TTIBI\Architecture\Commands;
use Illuminate\Console\Command;

class CrudGen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud  
        {path : Class (singular) for example User}
        {view_name : Class (singular) for example User}';


    private $controller_path = "Http/Controllers";
    private $service_path = "Services";

    private $serviceMapper = ['Dlr'=>'DLR','Inc'=>'INC','Admin'=>'ADMIN'];
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $path = $this->argument('path');
        $view_name = $this->argument('view_name');
        $paths = explode("/",$path);

        $module_name = $paths[0];

        if(!array_key_exists($module_name,$this->serviceMapper))
            $service_name = $paths[0];
        else
            $service_name = $this->serviceMapper[$paths[0]];
        

        $name = $paths[count($paths)-1];
        unset($paths[count($paths)-1]);
        unset($paths[0]);
        
        $paths = implode("/",$paths);
        $module = $module_name."/".$paths;
        $service = $service_name."/".$paths;
    
        
        $this->Services($service,$name);
        $this->Controller($name,$module,$service);
        $this->View(strtolower($module_name),$view_name);
        $this->JS(strtolower($module_name),$view_name);

    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    private function getControllerNamespace($module){
        $namespace = 'App/'.$this->getControllerFilePath($module);
        return str_replace("/","\\",$namespace);
    }

    private function getServiceNamespace($service,$name){
        $namespace = 'App/'.$this->getServiceFilePath($service,$name);
        return str_replace("/","\\",$namespace);
    }

    private function getControllerFilePath($module){
        return $this->controller_path."/".$module;
    }

    private function getServiceFilePath($service,$name){
        return $this->service_path."/".$service."/".$name;
    }

    private function getJSPath($module_name,$view_name){
        return public_path('js').'/'.$module_name.'/'.strtolower($view_name);
    }

    private function getViewPath($module_name){
        return resource_path('views').'/'.$module_name;
    }

    protected function Controller($name,$module,$service)
    {
        $template = $this->getStub('Controller');
        $template = str_replace(['{{name}}'],[$name],$template);
        $template = str_replace(['{{namespace}}'],$this->getControllerNamespace($module),$template);
        $template = str_replace(['{{service_namespace}}'],$this->getServiceNamespace($service,$name),$template);
        $path = app_path($this->getControllerFilePath($module));
        
        if (!file_exists($path)) {
            mkdir($path,0777, true);
        }

        file_put_contents($path."/{$name}.php", $template);
    }

    protected function Services($service,$name)
    {
        $template = $this->getStub('Service');
        $namespace = $this->getServiceNamespace($service,$name);
        $template = str_replace(['{{namespace}}'],$namespace,$template);    
        
        echo $path = app_path($this->getServiceFilePath($service,$name));
        
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        file_put_contents($path."/Service.php", $template);
    }

    protected function View($module_name,$view_name)
    {
        $path =  $this->getViewPath($module_name);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $template = $this->getStub('View');
        $template = str_replace(['{{module_name}}'],$module_name,$template);    
        $template = str_replace(['{{view_name}}'],$view_name,$template);    
        file_put_contents($path."/".$view_name.'.blade.php',$template);
    }

    protected function JS($module_name,$view_name){
        $path =  $this->getJSPath($module_name,$view_name);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        $helper_template = $this->getStub('HelperJS');
        $service_template = $this->getStub('ServiceJS');
        $validate_template = $this->getStub('ValidateJS');
        
        file_put_contents($path.'/helper.js',$helper_template);
        file_put_contents($path.'/service.js',$service_template);
        file_put_contents($path.'/validate.js',$validate_template);
    }

  


}
