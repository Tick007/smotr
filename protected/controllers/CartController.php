<?php 
class CartController extends Controller
{
    
    public function filters()
    {
        return array(
            //'accessControl', // perform access control for CRUD operations ////////////временно отключаем
            'getUser + profile',
        );
    }
    
    public function accessRules()
    {
        return array(
            /*
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('details'),
                'users'=>array('*'),
            ),
            */
            array('allow', // для авторизованных пользователей
                'actions'=>array('index','profile', 'Searcharhive'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    

    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionSearcharhive(){
        //	echo '111';
        $extent = Yii::app()->getRequest()->getParam('extent', NULL);
        //print_r($extent);
        $criteria=new CDbCriteria;
        //$criteria->condition = " t.id IN (".implode(',', $ids).")";
        $criteria->order="t.id";
        $models=Testimages::model()->findAll($criteria);
        for($i=0, $c=count($models); $i<$c; $i++ ){
            $extents[]=array(
                'extent'=>$models[$i]->coordinates,
                'geometry'=>$models[$i]->geometry,
                'filename'=>$models[$i]->filename,
                'shot_dt'=>$models[$i]->shot_dt,
                'file_id'=>$models[$i]->id,
                'legend'=>$models[$i]->legend,
            );
        }
        if(is_array($extents) && empty($extents)==false){
            //echo '<pre>';
            //print_r($extents);
            //echo '</pre>';
            
            for ($i=count($extents)-1; $i>=0; $i-- ) {
                $current_extent = str_replace('(', '', $extents[$i]['extent']);
                $current_extent = str_replace(')', '', $current_extent);
                $points=explode(',', $current_extent);
                $points_final[0]=$points[2];
                $points_final[1]=$points[3];
                $points_final[2]=$points[0];
                $points_final[3]=$points[1];
                $pf[$i]['extent'] = $points_final;
                $pf[$i]['filename'] = $extents[$i]['filename'];
                $pf[$i]['shot_dt'] = $extents[$i]['shot_dt'];
                $pf[$i]['file_id'] = $extents[$i]['file_id'];
                $pf[$i]['legend'] = $extents[$i]['legend'];
            }
            //echo '<pre>';
            //print_r($pf);
            //echo '</pre>';
            
            echo json_encode($pf);
            
            //$this->renderPartial('geojson', array('models'=>$models));
        }
        }
        
        //Метод для помещения выбранного файла в корзину пользователя
        ////перенесли их SiteController
        public function actionCartitem(){
            //тут в переменной файл(file) прилетает идентификатор файла который нужно засунуть в
            //корзину для текущего пользователя (1)
            $user_id = 1;////TODO пока, потом нужно будет смотреть какой залогинился
            $file = Yii::app()->getRequest()->getParam('file', NULL);
            if($file!=NULL){
                $file_id = (int)str_replace('file_', '', $file);
                if(is_numeric($file_id)){
                    ///////////////////и сразу смотрим есть ли этот файл в корзине
                    //////////////////TODO и потом еще нужно сделать ссмотреть этот файл в купленных для этого пользователя
                    $criteria=new CDbCriteria;
                    $criteria->condition = "t.file_id =:file_id AND t.user_id=:user_id";
                    $criteria->params = array(':file_id'=>$file_id, ':user_id'=>$user_id);
                    //$criteria->order="t.id";
                    $model=Cart::model()->find($criteria);
                    if($model==null){
                        $cart_model = new Cart();  
                        $cart_model->user_id = $user_id;
                        $cart_model->file_id = $file_id;
                        $cart_model->cost  = rand(1000,10000); //////////TODO где брать стоимость
                        $cart_model->save();
                        
                        ///И что нибудь возвращаем
                        echo json_encode('added');
                        exit();
                    }
                    elseif(is_null($model)==false){///////////////рас найдено, нужно удалить
                        $model->delete();
                        echo json_encode('deleted');
                        exit();
                    }
                }
               
            }
            else {
                throw new Exception('плохой идентификатор');
                exit();
            }
            
            
            //echo 'modified on ubuntu';
        
    }
    
}
?>