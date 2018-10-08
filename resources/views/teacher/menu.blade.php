<ul class="nav nav-tabs nav-justified">
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showGroups') }}">grupy</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTeachers') }}">nauczyciele</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showLessonPlans') }}">plan lekcji</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents') }}">uczniowie</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showEnlargements') }}">rozszerzenia</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showRatings') }}">oceny</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania</a></li>
</ul>
<p><a href="{{ route('klasa.index') }}">powrót</a></p>