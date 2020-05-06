<?php namespace App\Http\Controllers\Web;



use App\Http\Codes\Code;
use App\Http\Controllers\Controller;
use Libs\Response;
use App\Models\RbacNodeModel;
use Illuminate\Http\Request;

class RouteController extends Controller {

    public function module()
    {
        return 'web';
    }

    public function rule()
    {
    }

    public function content()
    {
        return file_get_contents(app_path() . '/../routes/admin.php');
    }

    public function callback(Request $request)
    {

        $rbac_node_model = new RbacNodeModel();

        $body            = $request->get('body');

        $routes          = json_decode($body, true);

        $miss = [];

        foreach ($routes as $module => $route){

            foreach ($route as $controller => $methods) {
                $son  = [];

                $controller_row = $rbac_node_model->where('module', $module)->where('pid', 0)->where('action', $controller)->first();

                $cid = $controller_row ? $controller_row->id : 0;

                foreach ($methods as $method => $arr){
                    $method_row = $rbac_node_model->where('module', $module)->where('pid', $cid)->where('action', $method)->first();

                    if($method_row)continue;

                    $son[] = [
                        'id'     => 0,
                        'module' => $module,
                        'action' => $method,
                        'son'    => []
                    ];

                }

                if($controller_row && !$son)continue;

                $tree = [
                    'id'      => $cid,
                    'module'  => $module,
                    'action'  => $controller,
                    'son'     => $son
                ];

                $miss[] = $tree;

            }

        }

        $this->update($miss);

        return Response::success('', $miss);
    }

    private function model()
    {
        static  $rbac_node_model;

        if(!$rbac_node_model){
            $rbac_node_model = new RbacNodeModel();
        }

        return $rbac_node_model;
    }

    private function update($nodes, $pid = 0)
    {
        $model = $this->model();

        foreach ($nodes as $node){
            $id = $node['id'];
            if(!$id){

                $row = $model->create([
                    'pid' => (int)$pid,
                    'module' => $node['module'],
                    'action' => $node['action'],
                ]);

                $id = $row->id;
            }

            $son = array_get($node,'son');

            if($son){
                $this->update($son, $id);
            }
        }
    }
}