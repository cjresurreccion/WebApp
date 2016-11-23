<?php

namespace App\Http\Controllers;

use \App\Task;
use Auth;
use File;
use App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
	
    /**
     * Create a new task instance.
     *
     * @param  Request  $request
     * @return Response
     */
    
	
	public function createTask(Request $request)
	{
		
		//display create task form
		return view('task_form');
		
	}
	
	public function sendTaskToFreshDesk(Task $task)
	{
		//send task to freshdesk via curl. Not the most elegant but works for now. :)
		$apikey="zNU7p6V6wUQANd4vAs7";  //generated from freshdesk admin panel
		$freshDeskTicketsURL="https://cjresurreccion.freshdesk.com/api/v2/tickets";
		
		$curl_command =	"curl -v -u ".$apikey.":X".
						" -H 'Content-Type: multipart/form-data'".
						" -F 'priority=1'". 
						" -F 'status=2'".
						" -F 'email=".$task->email."'".
						" -F 'subject=".$task->title."'".
						" -F 'description=".$task->desc."'".
						" -F 'type=".$task->type."'".
						" -F 'attachments[]=@".$task->attachmentFilePath."'".
						" -X POST '".$freshDeskTicketsURL."'";
						
		$output=shell_exec($curl_command);
		return $output;	
	}

	public function removeTaskFromFreshDesk(Task $task)
	{
		//send task to freshdesk via curl. Not the most elegant but works for now. :)
		$apikey="zNU7p6V6wUQANd4vAs7";  //generated from freshdesk admin panel
		$freshDeskTicketsURL="https://cjresurreccion.freshdesk.com/api/v2/tickets";
		
		$curl_command =	"curl -v -u ".$apikey.":X".
						" -X DELETE '".$freshDeskTicketsURL."/".$task->freshDeskId."'";
						
		$output=shell_exec($curl_command);
		return $output;	
	}

    public function storeTask(Request $request)
    {
		//return $request->all();
		$storageDirectory='/laravel/WebApp/storage/app';
		$storageSubDirectory='taskAttachments';

		//check if there is an attachment and upload attachment to laravel at storage/app/taskAttachments
		if( $request->attachmentFilePath ){
			$originalFileName=$request->file('attachmentFilePath')->getClientOriginalName();
			$request->file('attachmentFilePath')->storeAs($storageSubDirectory,$originalFileName);
		}
		
		//create new task	
        $task = new Task;
		$task->freshDeskId = 0; //initial ID. will need to be updated to actual freshDeskId 
		$task->title = $request->title;
		$task->desc = $request->desc;
		$task->email = $request->email;
		$task->type = 'Task'; // a custom ticket type correspondingly made in the freshdesk acct.
		
		//check if there is an attachment
		if( $request->attachmentFilePath ){
			$task->attachmentFilePath = $storageDirectory.'/'.$storageSubDirectory.'/'.$originalFileName;
		}else{
			
			$task->attachmentFilePath = '';
		}
		
		//save task attributes set above
        $task->save();
	
		//send task to freshdesk and get response to get freshDeskId	
		$response_json=$this->sendTaskToFreshDesk($task);
		
		//convert json to assoc array
		$response_obj=json_decode($response_json);
		
		//update freshDeskId on database
		$task->freshDeskId = $response_obj->{'id'};
		//save task attribute set above
		$task->save();
		
		//display new task on view
		return view('task_created', [
									'title' => $task->title,
									'desc' => $task->desc,
									'freshDeskId' => $response_obj->{'id'}
									]);
    }
	
    public function listTasks(Request $request)
    {
		//$max_records_to_show=30;
		$tasks = \App\Task::where('type', 'Task')  //show only tickets whose Type = "Task"
               ->orderBy('freshDeskId', 'asc') //order field, sort order (asc,desc)
          //     ->take($max_records_to_show)  //number of records to fetch
               ->get();

		return view('task_list', ["tasks"=>$tasks]);	   
	   
    }
	
	public function deleteTask(Request $request)
	{
		//find the task by its id
		$task = \App\Task::find($request->id);
		
		//delete attachment
		File::delete($task->attachmentFilePath);
		
		//remove from freshdesk
		
		//send task to freshdesk via curl. Not the most elegant but works for now. :)
		$apikey="zNU7p6V6wUQANd4vAs7";  //generated from freshdesk admin panel
		$freshDeskTicketsURL="https://cjresurreccion.freshdesk.com/api/v2/tickets";
		
		$this->removeTaskFromFreshDesk($task);
		
		//delete the task
		$task->delete();
		
		//show tasklist
		return $this->listTasks($request);
	}	
	
}