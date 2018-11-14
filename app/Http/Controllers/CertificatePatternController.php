<?php
namespace App\Http\Controllers;
//use App\Models\CertificatePattern;
//use App\Repositories\CertificatePatternRepository;
use Illuminate\Http\Request;

class CertificatePatternController extends Controller
{
    public function index(CertificatePatternRepository $patternRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("PatternOrderBy[$i]");

        $patterns = $patternRepo->getAll($orderBy);
        return view('certificatePattern.index', ["patterns"=>$patterns]);
    }

    public function orderBy($column)
    {
        if(session()->get('PatternOrderBy[0]') == $column)
          if(session()->get('PatternOrderBy[1]') == 'desc')
            session()->put('PatternOrderBy[1]', 'asc');
          else
            session()->put('PatternOrderBy[1]', 'desc');
        else
        {
          session()->put('PatternOrderBy[2]', session()->get('PatternOrderBy[0]'));
          session()->put('PatternOrderBy[0]', $column);
          session()->put('PatternOrderBy[3]', session()->get('PatternOrderBy[1]'));
          session()->put('PatternOrderBy[1]', 'asc');
        }
        return redirect( route('wzor_swiadectwa.index') );
    }

    public function create()
    {
        return view('certificatePattern.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:15',
          'destiny' => 'max:40',
        ]);

        $pattern = new CertificatePattern;
        $pattern->name = $request->name;
        $pattern->destiny = $request->destiny;
        $pattern->save();

        return redirect($request->history_view);
    }

    public function edit(CertificatePattern $wzor_swiadectwa)
    {
        return view('certificatePattern.edit', ["pattern"=>$wzor_swiadectwa]);
    }

    public function update(Request $request, CertificatePattern $wzor_swiadectwa)
    {
        $this->validate($request, [
          'name' => 'required|max:15',
          'destiny' => 'max:40',
        ]);

        $wzor_swiadectwa->name = $request->name;
        $wzor_swiadectwa->destiny = $request->destiny;
        $wzor_swiadectwa->save();

        return redirect($request->history_view);
    }
}
