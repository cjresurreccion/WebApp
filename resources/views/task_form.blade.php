@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Tasks</div>

                <div class="panel-body">
					<a href="/home">Back to Dashboard</a>
					<h1>Create Task</h1>
					<form method="POST" action="/api/store_task" enctype="multipart/form-data">
						<div class="form-group">
							Title
							<input type="text" name="title" class="form-control" />
							<br/>
							Description
							<textarea name="desc" class="form-control" rows=8></textarea>
							Attachment
							<input type="file" name="attachmentFilePath" />
							<input type="hidden" name="email" value="{{ Auth::user()->email }}" />  <!-- get user email from Auth object-->
							<button type="submit">Submit Task</button>
						</div>
					</form>
					<a href="/home">Back to Dashboard</a>

					
					
                </div>
				
            </div>
        </div>
    </div>
</div>
@endsection


