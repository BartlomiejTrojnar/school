@extends('layouts.app')

@section('java-script')
  <script src="{{ asset('public/js/groupCompare.js') }}"></script>
@endsection
@section('css')
  <link href="{{ asset('public/css/groupCompare.css') }}" rel="stylesheet">
@endsection

@section('header')
  <h1>Porównanie grup</h1>
@endsection

@section('main-content')
  <div id="divForDateView">
    Stan na <input type="date" id="dateView" value="{{ session()->get('dateView') }}" />
  </div>

  <div id="groupsForVerification">
    Wybierz klasę/grupę z której wyświetlić uczniów
    <form id="choice1">
      <?php echo $schoolYearSelectField; ?>
      <?php echo $gradeSelectField; ?>
    </form>
    <div id="listOfStudentsForVerification">
    </div>
  </div>

  <div id="groupsDispayed">
    <div id="groupDisplay1">
      <?php echo $gradeSelectFieldForGroup1; ?><br />
      <?php echo $groupSelectField1; ?>
      <div id="listOfStudentsForGroup1"></div>
    </div>

    <div id="groupDisplay2">
      <?php echo $gradeSelectFieldForGroup2; ?><br />
      <?php echo $groupSelectField2; ?>
      <div id="listOfStudentsForGroup2"></div>
    </div>

    <div id="groupDisplay3">
      <?php echo $gradeSelectFieldForGroup3; ?><br />
      <?php echo $groupSelectField3; ?>
      <div id="listOfStudentsForGroup3"></div>
    </div>

    <div id="groupDisplay4">
      <?php echo $gradeSelectFieldForGroup4; ?><br />
      <?php echo $groupSelectField4; ?>
      <div id="listOfStudentsForGroup4"></div>
    </div>
  </div>
@endsection