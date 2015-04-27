<?php
namespace Netinteractive\Seed\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Faker\Factory as Faker;

/**
 *
 * @package   Netinteractive\Seed
 * @subpackage Netinteractive\Seed\Commands
 * @version    0.0.1
 * @author Krzysztof Proczek
 */
class SimpleDataSeedCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:ni-data';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Netinteractive simple data seeder';

    public static $faker = null;
    
    /**
     * Default config filename if not set by argument.
     *
     * @var string
     */
    protected $config = 'simple-data-seeder';

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
     * Configures the current command.
     */
    protected function configure()
    {

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if(self::$faker === null){
            self::$faker = Faker::create(\Config::get('app.locale'));
        }
        
        $args = $this->argument();
        
        $configFile = isset($args['config-file']) ? $args['config-file'] : $this->config ;


        $config = \Config::get("ni-seed::".$configFile);
        
        \DB::connection()->disableQueryLog();

        foreach ($config AS $modelName=>$dataList){

            
            foreach ($dataList AS $data){

                $seeder = new \Netinteractive\Seed\Seeder();
                
                $model = \App::make($modelName);

                if(isset($data['keep_last']) && !$data['keep_last']) {
                    \DB::table($model->getTable())->delete();
                }

                if(!$data['repetitions']){
                    throw new \Exception(_('Nie podałeś ilości powtórzeń dla tabeli: '. $model->getTableName()));
                }
                
                $repetitions = $data['repetitions'];
                
                foreach(range(0, $repetitions) as $rep){
                    
                    $model = \App::make($modelName);
                    
                    // zapisujemy rekord
                    try{
                        $model->fill( $this->prepareData($data['data']) );
                        $model->save();

                        // sprawdzamy czy sa jakies rekordy powiazane, ktore laczymy przez attach
                        if (isSet($data['attach'])){
                            foreach ($data['attach'] AS $rel=>$elToAttachList){

                                if(is_callable($elToAttachList)){
                                    $elToAttachList = call_user_func($elToAttachList);
                                }

                                if ($elToAttachList instanceof \Netinteractive\Elegant\Collection){
                                    $elToAttachList = $elToAttachList->toArray();
                                }

                                foreach ($elToAttachList AS $toAttach){
                                    $model->$rel()->attach($toAttach['id']);
                                }
                            }
                        }

                        // sprawdzamy czy sa jakies rekordy powiazane, ktore trzeba utworzyc
                        if (isSet($data['create'])){
                            foreach ($data['create'] AS $relModelClass=>$elToCreateList){
                                if ( is_callable($elToCreateList) ){
                                    $elToCreateList = $elToCreateList();
                                }

                                if ($elToCreateList instanceof \Netinteractive\Elegant\Collection){
                                    $elToCreateList = $elToCreateList->toArray();
                                }

                                if($elToCreateList) {
                                    foreach ($elToCreateList AS $elToCreateData){
                                        $relModel = \App::make($relModelClass);

                                        $modelForeign = $model->getTable().'__id';
                                        $elToCreateData[$modelForeign] = $model->id;

                                        $relModel->fill( $this->prepareData($elToCreateData) );
                                        $relModel->save();
                                    }
                                }
                            }
                        }
                    }
                    catch (\Netinteractive\Elegant\Exception\ValidationException $exception){
                        print_R($data);
                        print_R($exception->getMessageBag());
                        exit;
                    }
                    
                    $seeder->progressBar($rep+1, $repetitions+1, $modelName);
                }                
            }
        }
    }

    /**
     * Prepare data to save
     * 
     * @return array
     */
    private function prepareData($data)
    {
        $newData = array();
        foreach($data as $k=>$v) 
        {
            if(is_string($v) && preg_match("/faker\:/", $v))
            { // szukaj metody fakera
                $newData[$k] = self::$faker->{$this->removeFakerPrefix($v)};
            } 
            else if(is_object($v) && $v instanceof \Closure) {
                $newData[$k] = $v();
            } 
            else if(is_array($v)) 
            { // losujemy z przekazanej tablicy
                $newData[$k] = $v[array_rand($v)];
            }
            else 
            { // ustawiamy wartość tak jak jest
                $newData[$k] = $v;
            }
        }
        return $newData;
    }

    private function removeFakerPrefix($string){
        return str_replace('faker:', '', $string);
    }
    
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(

        );
    }

}
