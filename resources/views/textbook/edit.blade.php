@extends('layouts.app')
@section('header')
  <h1>Modyfikacja podręcznika</h1>
@endsection

@section('main-content')
  <form action="{{ route('podrecznik.update', $textbook->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="author">autor</label></th>
        <td><input type="text" name="author" value="{{$textbook->author}}" size="40" maxlength="75" /></td>
      </tr>
      <tr>
        <th><label for="title">tytuł</label></th>
        <td><input type="text" name="title" value="{{$textbook->title}}" size="40" maxlength="125" required /></td>
      </tr>
      <tr>
        <th><label for="publishing_house">wydawnictwo</label></th>
        <td><input type="text" name="publishing_house" value="{{$textbook->publishing_house}}" size="15" maxlength="30" /></td>
      </tr>
      <tr>
        <th><label for="admission">dopuszczenie</label></th>
        <td><input type="text" name="admission" value="{{$textbook->admission}}" size="15" maxlength="18" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" value="{{$textbook->comments}}" size="40" maxlength="60" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('podrecznik.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection