<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    /**
     * DASHBOARD MULTI-ROLE
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Filter dasar untuk mengabaikan data placeholder di statistik dashboard
        $baseQuery = Applicant::where('status', '!=', 'placeholder');

        if ($user->role == 'direktur' || $user->role == 'director') {
            $totalCandidates = (clone $baseQuery)->count();
            $readyToRank = (clone $baseQuery)->where('status', 'ready')->count();
            $totalPositions = (clone $baseQuery)->distinct('position')->count('position');

            $topRanked = $this->getSAWRanking()->take(5);

            return view('director.dashboard', compact(
                'totalCandidates', 'readyToRank', 'totalPositions', 'topRanked'
            ));
        }

        if ($user->role == 'am' || $user->role == 'account_manager') {
            $pendingTechnical = (clone $baseQuery)->where('status', 'evaluated')->count();
            $totalAssessed = (clone $baseQuery)->whereNotNull('technical_score')->count();

            return view('am.dashboard', compact('pendingTechnical', 'totalAssessed'));
        }

        $totalCandidates = (clone $baseQuery)->count();
        $totalUsers = User::count();
        $pendingReview = (clone $baseQuery)->where('status', 'pending')->count();
        $totalPositions = (clone $baseQuery)->distinct('position')->count('position');
        $recentLogs = AuditLog::latest()->take(5)->get();
        $topCandidates = (clone $baseQuery)->orderBy('aptitude_score', 'desc')->take(5)->get();

        return view('hcm.dashboard', compact(
            'totalCandidates', 'totalUsers', 'pendingReview', 'totalPositions', 'topCandidates', 'recentLogs'
        ));
    }

    /**
     * CRUD CANDIDATES (HCM)
     */
    public function index(Request $request) {
        $search = $request->search;

        $applicants = Applicant::query()
            ->where('status', '!=', 'placeholder')
            ->when($search, function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                          ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $positions = Applicant::select('position')
            ->distinct()
            ->whereNotNull('position')
            ->where('status', '!=', 'placeholder')
            ->get();

        return view('hcm.candidates.index', compact('applicants', 'positions'));
    }

    public function create() {
        $positions = Applicant::select('position')->distinct()->whereNotNull('position')->get();
        return view('hcm.candidates.create', compact('positions'));
    }

    public function store(Request $request) {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:applicants,email',
            'phone'     => 'required|string|max:20',
            'position'  => 'required|string',
        ]);

        $applicant = Applicant::create([
            'full_name'      => $request->full_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'position'       => $request->position,
            'status'         => 'pending',
            'aptitude_score' => 0
        ]);

        // PERBAIKAN: Menyertakan $applicant agar log terisi Nama & Posisi
        $this->logActivity("Mendaftarkan kandidat baru: {$applicant->full_name}", $applicant);
        return redirect()->route('hcm.candidates')->with('success', 'Kandidat berhasil ditambahkan!');
    }

    public function show($id)
    {
        $applicant = Applicant::findOrFail($id);
        return view('hcm.candidates.show', compact('applicant'));
    }

    public function destroy($id) {
        $applicant = Applicant::findOrFail($id);
        $name = $applicant->full_name;

        // Simpan data untuk log sebelum dihapus
        $this->logActivity("Menghapus data kandidat: {$name}", $applicant);
        $applicant->delete();

        return redirect()->route('hcm.candidates')->with('success', 'Data kandidat berhasil dihapus!');
    }

    /**
     * MASTER DATA POSISI
     */
    public function indexPositions()
    {
        $allPositions = Applicant::select('position')
            ->distinct()
            ->whereNotNull('position')
            ->get();

        return view('hcm.positions.index', ['positions' => $allPositions]);
    }

    public function storePosition(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $placeholder = Applicant::create([
            'full_name' => 'SYSTEM_PLACEHOLDER',
            'email' => 'placeholder-' . time() . '@system.com',
            'phone' => '0000000000',
            'position' => $request->name,
            'status' => 'placeholder',
            'aptitude_score' => 0
        ]);

        $this->logActivity("Mendaftarkan jabatan baru: {$request->name}", $placeholder);
        return redirect()->back()->with('success', 'Jabatan ' . $request->name . ' berhasil ditambahkan!');
    }

    public function destroyPosition($position)
    {
        $decodedPosition = urldecode($position);
        $deletedCount = Applicant::where('position', $decodedPosition)->delete();

        $this->logActivity("Menghapus jabatan: {$decodedPosition} (Menghapus {$deletedCount} data)");
        return redirect()->back()->with('success', "Jabatan {$decodedPosition} berhasil dihapus.");
    }

    /**
     * TAHAP 1: HCM (Aptitude Test)
     */
    public function aptitude() {
        $applicants = Applicant::where('status', 'pending')->latest()->get();
        return view('hcm.aptitude.index', compact('applicants'));
    }

    public function updateAptitude(Request $request, $id) {
        $request->validate(['score' => 'required|numeric|min:0|max:100']);
        $applicant = Applicant::findOrFail($id);

        $applicant->update([
            'aptitude_score' => $request->score,
            'status' => 'evaluated'
        ]);

        // PERBAIKAN: Menyertakan $applicant agar log terisi Nama & Posisi
        $this->logActivity("Memberikan nilai Aptitude ({$request->score}) untuk: {$applicant->full_name}", $applicant);
        return redirect()->route('hcm.aptitude')->with('success', 'Nilai Aptitude berhasil disimpan!');
    }



    /**
     * TAHAP 2: ACCOUNT MANAGER (Technical Assessment)
     */
    public function indexAM() {
        $applicants = Applicant::where('status', 'evaluated')->latest()->get();
        return view('am.assessment.index', compact('applicants'));
    }

    public function updateTechnicalScore(Request $request, $id) {
        $request->validate([
            'score_1'  => 'required|numeric|min:0|max:100',
            'score_2'  => 'required|numeric|min:0|max:100',
            'score_3'  => 'required|numeric|min:0|max:100',
            'am_notes' => 'nullable|string|max:500',
        ]);

        $applicant = Applicant::findOrFail($id);
        $averageScore = ($request->score_1 + $request->score_2 + $request->score_3) / 3;

        $applicant->update([
            'score_1'         => $request->score_1,
            'score_2'         => $request->score_2,
            'score_3'         => $request->score_3,
            'technical_score' => $averageScore,
            'am_notes'        => $request->am_notes,
            'status'          => 'am_done'
        ]);

        // PERBAIKAN: Menyertakan $applicant agar log terisi Nama & Posisi
        $this->logActivity("Input nilai Teknis untuk: {$applicant->full_name}", $applicant);
        return redirect()->route('am.assessment')->with('success', "Penilaian {$applicant->full_name} berhasil!");
    }

    public function amHistory() {
        $history = Applicant::whereIn('status', ['am_done', 'ready'])
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('am.assessment.history', compact('history'));
    }

    /**
     * TAHAP 3: DIREKTUR (Final Review & SAW)
     */
    public function assessment() {
        $applicants = Applicant::where('status', 'am_done')->latest()->get();
        return view('director.assessment.index', compact('applicants'));
    }

    public function updateFinalAssessment(Request $request, $id) {
        $request->validate([
            'experience_score'   => 'required|numeric|min:0|max:100',
            'interview_score'    => 'required|numeric|min:0|max:100',
            'salary_expectation' => 'required|numeric|min:0',
        ]);

        $applicant = Applicant::findOrFail($id);
        $applicant->update([
            'experience_score'   => $request->experience_score,
            'interview_score'    => $request->interview_score,
            'salary_expectation' => $request->salary_expectation,
            'status'             => 'ready'
        ]);

        // PERBAIKAN: Menyertakan $applicant agar log terisi Nama & Posisi
        $this->logActivity("Final Penilaian SAW untuk: {$applicant->full_name}", $applicant);
        return redirect()->route('director.assessment')->with('success', 'Seluruh kriteria berhasil dilengkapi!');
    }

    public function showRanking() {
        $results = $this->getSAWRanking();
        $rankingResults = $results->map(function ($item) {
            return [
                'full_name' => $item->full_name,
                'email'     => $item->email,
                'position'  => $item->position,
                'v_score'   => number_format($item->v_score, 2),
            ];
        })->values()->all();

        return view('director.ranking', compact('rankingResults'));
    }

    private function getSAWRanking() {
        $applicants = Applicant::where('status', 'ready')->get();
        if ($applicants->isEmpty()) return collect();

        $weights = ['c1' => 0.30, 'c2' => 0.10, 'c3' => 0.30, 'c4' => 0.20, 'c5' => 0.10];

        $maxC1 = $applicants->max('aptitude_score') ?: 1;
        $maxC2 = $applicants->max('experience_score') ?: 1;
        $maxC3 = $applicants->max('technical_score') ?: 1;
        $maxC4 = $applicants->max('interview_score') ?: 1;
        $minC5 = $applicants->min('salary_expectation') ?: 1;

        return $applicants->map(function ($item) use ($weights, $maxC1, $maxC2, $maxC3, $maxC4, $minC5) {
            $nC1 = $item->aptitude_score / $maxC1;
            $nC2 = $item->experience_score / $maxC2;
            $nC3 = $item->technical_score / $maxC3;
            $nC4 = $item->interview_score / $maxC4;
            $nC5 = $item->salary_expectation > 0 ? $minC5 / $item->salary_expectation : 0;

            $totalV = ($nC1 * $weights['c1']) + ($nC2 * $weights['c2']) + ($nC3 * $weights['c3']) + ($nC4 * $weights['c4']) + ($nC5 * $weights['c5']);
            $item->v_score = $totalV * 100;
            return $item;
        })->sortByDesc('v_score');
    }

    public function showLogs()
    {
        $logs = AuditLog::latest()->take(100)->get();
        return view('hcm.logs.index', compact('logs'));
    }

    /**
     * FUNGSI PENCATATAN AKTIVITAS
     */
    private function logActivity($action, $applicant = null)
    {
        AuditLog::create([
            'user_name' => Auth::user()->name,
            'action'    => $action,
            'full_name' => $applicant->full_name ?? null,
            'email'     => $applicant->email ?? null,
            'position'  => $applicant->position ?? null,
            'status'    => $applicant->status ?? 'pending',
        ]);
    }
public function showLogDetail($id)
{
    $log = \App\Models\AuditLog::findOrFail($id);

    // Pastikan email ini ada di tabel applicants
    $applicant = \App\Models\Applicant::where('email', $log->email)->first();

    if (!$applicant) {
        return redirect()->back()->with('error', 'Kandidat tidak ditemukan.');
    }

    return view('hcm.candidates.show', compact('applicant'));
}

}
