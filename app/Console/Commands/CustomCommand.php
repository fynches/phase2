<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use Carbon\Carbon;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CustomCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set cron job on daily basis to check event expired or not';

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
        
        $events = Event::whereDate('event_end_date', '<', Carbon::now())->get()->toArray(); 
		
        foreach ($events as $key => $value) {
        	
        	$event_end_date= $value['event_end_date'];
			$due_date= date('Y-m-d',strtotime('+30 days',strtotime($event_end_date)));
			$today_date= date('Y-m-d');
			
			if($due_date < $today_date)
			{
				$event = Event::find($value['id']);
            	$event->status = 3;
            	$event->save();
			}
			
        }
        $this->info('All event set in cron to checking event is expired or not.');
    }
}
