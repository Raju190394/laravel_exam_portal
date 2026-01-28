<!DOCTYPE html>
<html>
<head>
    <title>Exam Report - {{ $exam->title }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .score { font-weight: bold; }
        .pass { color: green; }
        .fail { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exam Performance Report</h1>
        <h2>{{ $exam->title }}</h2>
        <p>Course: {{ $exam->course->name }} | Date: {{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            @php $p = round(($result->score / $exam->total_marks) * 100, 2); @endphp
            <tr>
                <td>{{ $result->user->name }}</td>
                <td>{{ $result->user->email }}</td>
                <td class="score">{{ $result->score }} / {{ $exam->total_marks }}</td>
                <td>{{ $p }}%</td>
                <td class="{{ $p >= $exam->passing_marks ? 'pass' : 'fail' }}">
                    {{ $p >= $exam->passing_marks ? 'PASSED' : 'FAILED' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
