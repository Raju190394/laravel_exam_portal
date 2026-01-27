<!DOCTYPE html>
<html>
<head>
    <title>Result - {{ $studentExam->exam->title }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #6366f1; margin-bottom: 30px; padding-bottom: 20px; }
        .student-info { margin-bottom: 30px; }
        .score-box { background: #f8fafc; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0; }
        .score-value { font-size: 32px; font-weight: bold; color: #6366f1; }
        .pass { color: #10b981; font-weight: bold; }
        .fail { color: #ef4444; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EXAMINATION RESULT</h1>
            <h3>{{ $studentExam->exam->title }}</h3>
            <p>{{ $studentExam->exam->course->name }}</p>
        </div>

        <div class="student-info">
            <p><strong>Student Name:</strong> {{ $studentExam->user->name }}</p>
            <p><strong>Email:</strong> {{ $studentExam->user->email }}</p>
            <p><strong>Date Taken:</strong> {{ $studentExam->completed_at->format('d M Y, H:i') }}</p>
        </div>

        <div class="score-box">
            @php $p = round(($studentExam->score / $studentExam->exam->total_marks) * 100, 2); @endphp
            <div>Your Scored</div>
            <div class="score-value">{{ $studentExam->score }} / {{ $studentExam->exam->total_marks }}</div>
            <div style="font-size: 18px; margin-top: 10px;">{{ $p }}%</div>
            <div style="margin-top: 10px;" class="{{ $p >= $studentExam->exam->passing_marks ? 'pass' : 'fail' }}">
                Status: {{ $p >= $studentExam->exam->passing_marks ? 'PASSED' : 'FAILED' }}
            </div>
        </div>

        <p style="text-align: center; color: #64748b;">This is a computer-generated document and does not require a physical signature.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} - Online Examination System
        </div>
    </div>
</body>
</html>
