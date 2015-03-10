<?php
namespace Netinteractive\Seed\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 *
 * @package   Netinteractive\Seed
 * @subpackage Netinteractive\Seed\Commands
 * @version    0.0.1
 * @author     Piotr Pryga
 */
class TestDataSeedCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:ni-test-data';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Netinteractive unit test data seeder';

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
        if (!\App::environment('testing')){
            throw new \Exception(_('Komenda działa jedynie dla środowiska testing. Odpal z parameterm --env=testing'));
        }

        $config = \Config::get('ni-seed::test');

        \DB::connection()->disableQueryLog();

        foreach ($config AS $modelName=>$dataList){

            foreach ($dataList AS $data){

                $model = \App::make($modelName);
                 \DB::table($model->getTable())->delete();

                #zapisujemy rekord
                try{
                    $model->fill($data['data']);
                    $model->save();

                    #sprawdzamy czy sa jakies rekordy powiazane, ktore laczymy przez attach
                    if (isSet($data['attach'])){
                        foreach ($data['attach'] AS $rel=>$elToAttachList){
                            if ($elToAttachList instanceof \Netinteractive\Elegant\Collection){
                                $elToAttachList = $elToAttachList->toArray();
                            }

                            foreach ($elToAttachList AS $toAttach){
                                $model->$rel()->attach($toAttach['id']);
                            }
                        }
                    }

                    #sprawdzamy czy sa jakies rekordy powiazane, ktore trzeba utworzyc
                    if (isSet($data['create'])){
                        foreach ($data['create'] AS $relModelClass=>$elToCreateList){
                            if ( is_callable($elToCreateList) ){
                                $elToCreateList = $elToCreateList();
                            }

                            if ($elToCreateList instanceof \Netinteractive\Elegant\Collection){
                                $elToCreateList = $elToCreateList->toArray();
                            }


                            foreach ($elToCreateList AS $elToCreateData){
                                $relModel = \App::make($relModelClass);

                                $modelForeign = $model->getTable().'__id';
                                $elToCreateData[$modelForeign] = $model->id;

                                $relModel->fill($elToCreateData);
                                $relModel->save();
                            }

                        }
                    }
                }
                catch (Netinteractive\Elegant\Exception\ValidationException $exception){
                    print_R($data);
                    print_R($exception->getMessageBag());
                    exit;
                }
            }

        }
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
