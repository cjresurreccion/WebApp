@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Tasks</div>

                <div class="panel-body">
                    
					<h1>New Task Created</h1>
					
						<div class="form-group">
							Title : {{ $title }}
							<br/>
							Description : {{ $desc }}
							<br/>
							freshDeskId : {{ $freshDeskId }}
							<br/>
							<a href="/home">Back to Dashboard</a>
							
						</div>
					
					
                </div>
				
            </div>
        </div>
    </div>
</div>
@endsection
