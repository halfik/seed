<?php namespace Netinteractive\Seed\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 *
 * @package   Netinteractive\Seed
 * @subpackage Netinteractive\Seed\Commands
 * @version    1.0.0
 * @author     Piotr Pryga
 */
class TestDataSeedCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:ni-seed:test-data';


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
            throw new \Exception(_('Command is avaible only for testing environment. Use it with --env=testing parameter.'));
        }


        $configName = $this->option('config');
        if(!$configName){
            $configName = 'packages.netinteractive.seed.test';
        }

        $config = \Config::get($configName);

        \DB::connection()->disableQueryLog();
        if (!isSet($config['data']) || !is_array($config) || count($config['data'])==0){
            throw new \Exception(_('Config file errror: no data found.'));
        }

        if (isSet($config['tables'] )){
            $this->createTables($config['tables']);
        }

        $this->seedData($config['data']);

    }

    /**
     * Creates tables
     * @param $list
     */
    protected function createTables($list)
    {
        $serializer = new \SuperClosure\Serializer(null, null);

        foreach ($list AS $name=>$func){
            $funcToCall = $serializer->unserialize($func);

            if (is_callable($funcToCall)){
                $funcToCall();
            }
        }
    }

    /**
     * Seeds data
     * @param array $data
     */
    protected function seedData(array $seedData)
    {

        foreach ($seedData AS $recordClass=>$dataList){
            $dbMapper = new \Netinteractive\Elegant\Mapper\DbMapper($recordClass);

            $dbMapper->getQuery()->delete();

            foreach ($dataList AS $data){

                #record save
                try{
                    $record = $dbMapper->getRecord();
                    $record->fill($data['data']);

                    $dbMapper->save($record);

                    #here we check if there are any related data we should attach to record
                   /* if (isSet($data['attach'])){
                        foreach ($data['attach'] AS $rel=>$elToAttachList){
                            if ($elToAttachList instanceof \Illuminate\Support\Collection){
                                $elToAttachList = $elToAttachList->toArray();
                            }

                            foreach ($elToAttachList AS $toAttach){
                                $model->$rel()->attach($toAttach['id']);
                            }
                        }
                    }

                    #here we check if there are any related data we have to create
                    if (isSet($data['create'])){
                        foreach ($data['create'] AS $relModelClass=>$elToCreateList){
                            if ( is_callable($elToCreateList) ){
                                $elToCreateList = $elToCreateList();
                            }

                            if ($elToCreateList instanceof \Illuminate\Support\Collection){
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
                   */
                }
                catch (\Netinteractive\Elegant\Exception\ValidationException $exception){
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
            array('config', null, InputOption::VALUE_OPTIONAL, 'config.', null),
        );
    }

}
