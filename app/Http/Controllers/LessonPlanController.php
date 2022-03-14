<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 04.03.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\LessonPlan;
use App\Repositories\LessonPlanRepository;

use App\Repositories\GroupRepository;
use App\Repositories\GroupStudentRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function findClassroomLesson(Request $request, LessonPlanRepository $lessonPlanRepo) {
        $lessons = $lessonPlanRepo -> findClassroomLesson($request->classroom_id, $request->lesson_hour_id, $request->dateView);
        if(empty($lessons)) return ;
        return view('lessonPlan.classroomLesson', ["lessons"=>$lessons]);
    }

    public function findClassroomLessonsForHour(Request $request, LessonPlanRepository $lessonPlanRepo) {
        $lessons = $lessonPlanRepo -> findClassroomLessonsForHour($request->classroom_id, $request->lesson_hour_id, $request->dateView);
        if(empty($lessons)) return ;
        return view('lessonPlan.classroomLessonsForHour', ["lessons"=>$lessons]);
    }

    public function findGroupsToAssign(Request $request, GroupRepository $groupRepo) {
       $groups = $groupRepo -> getGroupsForGrade($request->grade_id);
       return view('lessonPlan.groupsToAssign', ["groups"=>$groups]);
    }

    public function findGroupLessonForHour(Request $request, LessonPlanRepository $lessonPlanRepo) {
        $lessons = $lessonPlanRepo -> findGroupLessonForHour($request->group_id, $request->lesson_hour_id, $request->dateView);
        if(empty($lessons)) return 0;
        $studyYear = substr($request->dateView,0,4) - $lessons[0]->group->grades[0]->grade->year_of_beginning;
        if( substr($request->dateView,5,2)>=8 ) $studyYear++;
        return view('lessonPlan.groupLessonsForHour', ["lessons"=>$lessons, "lesson_hour_id"=>$request->lesson_hour_id, "studyYear"=>$studyYear, "dateView"=>$request->dateView]);
    }

    public function setClassroomToLesson(Request $request, LessonPlan $lessonPlan) {
        $lessonPlan = $lessonPlan -> find($request->lesson_id);
        if($request->classroom_id<0)  $lessonPlan->classroom_id = NULL;
        else $lessonPlan->classroom_id = $request->classroom_id;
        $lessonPlan -> save();
        return $lessonPlan->classroom_id;
    }

    public function setTheEndDateOfTheLesson(Request $request, LessonPlan $lessonPlan) {
        $lessonPlan = $lessonPlan -> find($request->lesson_id);
        $lessonPlan->end = $request->end;
        if($lessonPlan->end < $lessonPlan->start)  $lessonPlan -> delete();
        else {
            print_r($lessonPlan->end < $lessonPlan->start);
            $lessonPlan -> save();
        }
        return 1;
    }

    public function addLesson(Request $request, GroupRepository $groupRepo) {
        $lessonPlan = new LessonPlan;
        $lessonPlan->group_id = $request->group_id;
        $lessonPlan->lesson_hour_id = $request->lesson_hour_id;
        $lessonPlan->start = $request->start;
        $lessonPlan->classroom_id = NULL;

        $group = $groupRepo -> find($request->group_id);
        $lessonPlan->end = $group->end;

        if( $lessonPlan->start > $lessonPlan->end ) return 0;
        $lessonPlan -> save();
        return $lessonPlan->id;
    }

    public function cloneLesson(Request $request, LessonPlan $lessonPlan) {
        print_r(0);
        $oldLessonPlan = $lessonPlan -> find($request->lesson_id);
        $newLessonPlan = new LessonPlan;
        $newLessonPlan->group_id = $oldLessonPlan->group_id;
        $newLessonPlan->lesson_hour_id = $request->lesson_hour_id;
        if(empty($request->classroom_id)) $newLessonPlan->classroom_id = $oldLessonPlan->classroom_id;
        else $newLessonPlan->classroom_id = $request->classroom_id;
        if( $request->classroom_id==-1 ) $newLessonPlan->classroom_id = NULL;
        $newLessonPlan->start = $request->start;
        $newLessonPlan->end = $oldLessonPlan->end;

        if( $newLessonPlan->start > $newLessonPlan->end ) return -9;
        if( $newLessonPlan->start < $newLessonPlan->group->start) return -1;
        if( $newLessonPlan->start > $newLessonPlan->group->end) return -2;
        if( $newLessonPlan->end < $newLessonPlan->group->start) return -3;
        if( $newLessonPlan->end > $newLessonPlan->group->end) return -4;
        $newLessonPlan -> save();
        return $newLessonPlan->id;
    }

    public function getDateEnd(Request $request, SchoolYearRepository $schoolYearRepo, GroupRepository $groupRepo) {
        $proposedDates = $schoolYearRepo -> getDatesOfSchoolYear($request->dateView);
        $group = $groupRepo -> find($request->group_id);
        if( $group->date_end > $proposedDates['dateOfGraduationSchoolYear'] )
            return $proposedDates['dateOfGraduationSchoolYear'];
        else
            return $group->date_end;
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'group_id' => 'required',
          'lesson_hour_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $lessonPlan = new LessonPlan;
        $lessonPlan->group_id = $request->group_id;
        $lessonPlan->lesson_hour_id = $request->lesson_hour_id;
        $lessonPlan->classroom_id = $request->classroom_id;
          if($request->classroom_id==0) $lessonPlan->classroom_id=NULL;
        $lessonPlan->date_start = $request->date_start;
        $lessonPlan->date_end = $request->date_end;
        $lessonPlan -> save();
        return $lessonPlan;
    }

    public function update($id, Request $request, LessonPlan $lessonPlan) {
        $this -> validate($request, [
          'group_id' => 'required',
          'lesson_hour_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
        ]);

        $lessonPlan = $lessonPlan -> find($id);
        $lessonPlan->group_id = $request->group_id;
        $lessonPlan->lesson_hour_id = $request->lesson_hour_id;
        if($request->classroom_id==0) $lessonPlan->classroom_id=NULL;
        else $lessonPlan->classroom_id = $request->classroom_id;
        $lessonPlan->start = $request->start;
        $lessonPlan->end = $request->end;
        $lessonPlan -> save();
        return $lessonPlan->id;
    }

    public function destroy($id, LessonPlan $lessonPlan) {
        $lessonPlan = $lessonPlan -> find($id);
        $lessonPlan -> delete();
        return;
    }

    public function showStudentListForGroup(Request $request, GroupStudentRepository $groupStudentRepo, SchoolYearRepository $schoolYearRepo) {
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate( $request->dateView );
        $groupStudents = $groupStudentRepo -> getGroupStudents($request->group_id, $request->dateView);
        return view('groupStudent.listForLessonPlan', ["groupStudents"=>$groupStudents, "schoolYear"=>$schoolYear, "dateView"=>$request->dateView, "group_id"=>$request->group_id, "lesson_hour_id"=>$request->lesson_hour_id]);
    }
}