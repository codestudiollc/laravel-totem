@extends('totem::layout')
@section('page-title')
    @parent
    - Task
@stop
@section('title')
    <div class="uk-flex uk-flex-between uk-flex-middle">
        <h5 class="uk-card-title uk-margin-remove">Task Details</h5>
        <status-button :data-task="{{ $task }}" :data-exists="{{ $task->exists ? 1 : 0 }}"></status-button>
    </div>
@stop
@section('main-panel-content')
    <ul class="uk-list uk-list-striped">
        <li>
            <span class="uk-text-muted uk-float-right">Description</span>
            <span class="uk-float-left">{{$task->description}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Command</span>
            <span class="uk-float-left">{{$task->command}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Parameters</span>
            <span class="uk-float-left">{{$task->parameters or 'N/A'}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Cron Expression</span>
            <span class="uk-float-left">{{$task->cron}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Timezone</span>
            <span class="uk-float-left">{{$task->timezone}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Created At</span>
            <span class="uk-float-left">{{$task->created_at->toDateTimeString()}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Updated At</span>
            <span class="uk-float-left">{{$task->updated_at->toDateTimeString()}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Email to receive task output</span>
            <span class="uk-float-left">{{$task->notification_email_address or 'N/A'}}</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Average Run Time</span>
            <span class="uk-float-left">{{$task->results->count() > 0 ? number_format(  $task->results->sum('duration') / (1000 * $task->results->count()) , 2) : '0'}} seconds</span>
        </li>
        <li>
            <span class="uk-text-muted uk-float-right">Next Run Schedule</span>
            <span class="uk-float-left">{{$task->upcoming }}</span>
        </li>
        @if($task->dont_overlap)
            <li>
                <span class="uk-float-left">Doesn't Overlap with another instance of this task</span>
            </li>
        @endif
        @if($task->run_in_maintenance)
            <li>
                <span class="uk-float-left">Runs in maintenance mode</span>
            </li>
        @endif
    </ul>
@stop
@section('main-panel-footer')
    <div class="uk-flex uk-flex-between uk-flex-middle">
        <span>
            <a href="{{ route('totem.task.edit', $task) }}" class="uk-button uk-button-primary uk-button-small">Edit</a>
            <form class="uk-display-inline" action="{{route('totem.task.delete', $task)}}" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button type="submit" class="uk-button uk-button-danger uk-button-small">Delete</button>
            </form>
        </span>
        <a href="{{ route('totem.task.run', $task) }}" class="uk-button uk-button-primary uk-button-small">Execute</a>
    </div>
@stop
@section('additional-panels')
    <div class="uk-card uk-card-default uk-margin-top">
        <div class="uk-card-header">
            <h5 class="uk-card-title uk-margin-remove">Execution Results</h5>
        </div>
        <div class="uk-card-body uk-padding-remove-top">
            <table class="uk-table uk-table-striped">
                <thead>
                    <tr>
                        <th>Executed At</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($results = $task->results()->orderByDesc('created_at')->paginate(10) as $result)
                    <tr>
                        <td>{{$result->ran_at->toDateTimeString()}}</td>
                        <td>{{ number_format($result->duration / 1000 , 2)}} seconds</td>
                    </tr>
                @empty
                    <tr>
                        <td class="uk-text-center" colspan="5">
                            <p>Not executed yet.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="uk-card-footer">
            {{$results->links('totem::partials.pagination')}}
        </div>
    </div>
@stop