<?php
namespace App\Http\Controllers;
//use App\Models\Achievement;
//use App\Repositories\AchievementRepository;
//use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
/*
    public function index(AchievementRepository $achievementRepo)
    {
        $achievements = $achievementRepo -> getAllSorted();
        return view('achievement.index')
            -> nest('achievementTable', 'achievement.table', ["achievements"=>$achievements, "subTitle"=>""]);
    }

    public function orderBy($column)
    {
        if(session()->get('AchievementOrderBy[0]') == $column)
          if(session()->get('AchievementOrderBy[1]') == 'desc')
            session()->put('AchievementOrderBy[1]', 'asc');
          else
            session()->put('AchievementOrderBy[1]', 'desc');
        else
        {
          session()->put('AchievementOrderBy[2]', session()->get('AchievementOrderBy[0]'));
          session()->put('AchievementOrderBy[0]', $column);
          session()->put('AchievementOrderBy[3]', session()->get('AchievementOrderBy[1]'));
          session()->put('AchievementOrderBy[1]', 'asc');
        }
        return redirect( route('osiagniecie.index') );
    }

    public function create(CertificateRepository $certificateRepo)
    {
        $certificates = $certificateRepo->getAll();
        return view('achievement.create')
             ->nest('certificateSelectField', 'certificate.selectField', ["certificates"=>$certificates, "selectedCertificate"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'certificate_id' => 'required',
          'inscription' => 'required|max:200',
        ]);

        $achievement = new Achievement;
        $achievement->certificate_id = $request->certificate_id;
        $achievement->inscription = $request->inscription;
        $achievement->save();

        return redirect($request->history_view);
    }

    public function show(Achievement $osiagniecie)
    {
        $previous = $achievementRepo->previousRecordId($achievement->id);
        $next = $achievementRepo->nextRecordId($achievement->id);
        return view('achievement.show', ["achievement"=>$achievement, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Achievement $osiagniecie, CertificateRepository $certificateRepo)
    {
        $certificates = $certificateRepo->getAll();
        return view('achievement.edit', ["achievement"=>$osiagniecie])
             ->nest('certificateSelectField', 'certificate.selectField', ["certificates"=>$certificates, "selectedCertificate"=>$osiagniecie->certificate_id]);
    }

    public function update(Request $request, Achievement $osiagniecie)
    {
        $this->validate($request, [
          'certificate_id' => 'required',
          'inscription' => 'required|max:200',
        ]);

        $osiagniecie->certificate_id = $request->certificate_id;
        $osiagniecie->inscription = $request->inscription;
        $osiagniecie->save();

        return redirect($request->history_view);
    }

    public function destroy(Achievement $osiagniecie)
    {
        $osiagniecie->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
