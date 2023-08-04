<div class="task-progress-column">
    <div class="task-progress-column-heading">
      <h2>{{ $title }}</h2>
      <a href="{{ route('tasks.create',['status'=> $status])}}"><button><span class="material-icons task-progress-card-top-edit">add</span></button></a>
    </div>
    <div>
      @foreach ($tasks as $task)
        @include('partials.task_card', [
          'task' => $task,
          'leftStatus' => $leftStatus,
          'rightStatus' => $rightStatus,
        ])
      @endforeach
    </div>
  </div>