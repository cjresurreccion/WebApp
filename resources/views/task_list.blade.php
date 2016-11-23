@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Tasks</div>

                <div class="panel-body">
					<a href="/home">Back to Dashboard</a>
					<h1>Task List</h1>
					<table border=1>
						<tr>
							<th></th>
							<th>freshDeskId</th>
							<th>title</th>
							<th>description</th>

						</tr>
						<!-- 
							Loop through each element of $tasks from list_tasks method of TaskController.php
							and generate a new table row for each
						-->
						@foreach($tasks as $task)
							<tr>
								<td><a href='/api/delete_task/{{ $task->id }}'>Delete</a></td>
								<td>{{ $task->freshDeskId }}</td>
								<td>{{ $task->title }}</td>
								<td>{{ $task->desc }}</td>
							</tr>

						@endforeach
						
					</table>
					<a href="/home">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


							