<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: no-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .page-content { flex: 1; }
    .table-scroll { overflow-x: auto; background: #ffffff; border-radius: 6px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 13px; }
    thead th { background: #0f2a44; color: white; }
    .no-pdf { text-align: center; margin-top: 20px; }

    /* ===== PDF STYLE ===== */
    .pdf-style h3, .pdf-style h5, .pdf-style p { text-align: center; font-family: "Times New Roman", Times, serif; color: black; }
    .pdf-style table { font-size: 12px; }
    .pdf-style th { background: #e9ecef !important; color: black !important; font-weight: bold; }
    .pdf-style td:first-child { font-weight: bold; background: #f2f2f2; }
    .pdf-style * { box-shadow: none !important; }

    /* ===== PRINT BUTTONS ===== */
    .nodis { display: block; }
    @media print { .nodis { display: none !important; } }

        body {
            background: #f8fafc; /* academic light */
            color: #1e293b;
        }

        /* HEADER */
        header {
            background: #0f2a44; /* navy blue */
            color: white;
            padding: 15px 20px;
            border-bottom: 4px solid #c9a227; /* gold line */
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav h1 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            gap: 18px;
        }

        .nav ul li a {
            color: #f8fafc;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
        }

        .nav ul li a:hover {
            color: #c9a227; /* gold hover */
        }

        /* HERO */
        .hero {
            background: linear-gradient(to right, #0f2a44, #143a5e);
            color: white;
            text-align: center;
            padding: 90px 20px;
        }

        .hero h2 {
            font-size: 34px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 16px;
            max-width: 650px;
            margin: auto;
            color: #e5e7eb;
        }

        /* CONTENT */
        .container {
            padding: 50px 20px;
            max-width: 1100px;
            margin: auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #c9a227; /* academic accent */
        }

        .card h3 {
            margin-bottom: 12px;
            color: #0f2a44;
        }

        .card p {
            color: #475569;
        }

        /* FOOTER */
        footer {
            background: #0f2a44;
            color: #e5e7eb;
            text-align: center;
            padding: 18px;
            margin-top: 50px;
        }

        /* RESPONSIVE NAV */
        @media (max-width: 600px) {
            .nav ul {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .hero h2 {
                font-size: 26px;
            }
        }
        .chart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
        }
    </style>
</head>
<body>
   <div class="page-wrapper">

    <div class="page-content">
        <section class="container">

            <div class="table-scroll shadow">
                <div style="text-align: center;">
                    <img src="{{ asset('images/ipalogo1.png') }}" alt="" width="120">
                </div>

                <div class="table-responsive">
                    <section class="container">

                        <div id="pdfContent">
                            <h3 style="text-align: center">INSTITUTE OF PUBLIC AND ADMINISTRATION</h3>
                            <h5 style="text-align: center">TEACHER SUBJECT: <b>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</b></h5><br>

                            <div class="table-scroll shadow">
                                <table id="timetableTable">
                                    <thead>
                                        <tr>
                                            <th>DAY / TIME</th>
                                            @foreach($timetable['timeslots'] as $slot)
                                                <th>
                                                    {{ date('H:i', strtotime($slot['start'])) }} -
                                                    {{ date('H:i', strtotime($slot['end'])) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($timetable['entries'] as $day => $dayEntries)
                                            <tr>
                                                <td>{{ strtoupper($day) }}</td>
                                                @foreach($timetable['timeslots'] as $slot)
                                                    <td>
                                                        @php
                                                            $found = $dayEntries
                                                                ->where('start_time', $slot['start'])
                                                                ->where('end_time', $slot['end'])
                                                                ->first();
                                                        @endphp
                                                        @if($found)
                                                            <strong>{{ $found->subjectName }}</strong><br>
                                                            {{ $found->courseName }} (NTA {{ $found->nta_level }})<br>
                                                            @if($found->group_name)
                                                                GROUP {{ strtoupper($found->group_name) }}<br>
                                                            @endif
                                                            {{ $found->room_name }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- DOWNLOAD PDF BUTTON -->
                        <div class="no-pdf">
                            

                            <!-- PRINT BUTTON -->
                            <button 
                                class="btn btn-primary btn-sm"
                                onclick="printTimetable(this)"
                                data-timetable='@json($timetable["entries"])'
                                data-course="{{ $timetable['course'] ?? 'COURSE NAME' }}"
                                data-nta="{{ $timetable['nta_level'] ?? 'NTA LEVEL' }}"
                                data-group="{{ $timetable['group_name'] ?? '' }}"
                                data-semester="{{ $activeSemester->semName ?? 'SEMESTER UNKNOWN' }}"
                                data-active="{{ $activeSemester->academic_year ?? 'YEAR UNKNOWN' }}"
                            >
                                <i class="bi bi-printer"></i> Print Timetable
                            </button>
                        </div>

                    </section>
                </div>
            </div>

        </section>
    </div>

    <footer class="bg-dark text-light py-3">
        <p class="text-center mb-0">&copy; 2025 ShamisTech. All Rights Reserved.</p>
    </footer>

</div>

<!-- PDF LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
async function downloadPDF() {
    const pdfContent = document.getElementById('pdfContent');
    pdfContent.classList.add('pdf-style');

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('landscape', 'mm', 'a4');

    const canvas = await html2canvas(pdfContent, { scale: 2, useCORS: true });
    const imgData = canvas.toDataURL('image/png');
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = (canvas.height * pageWidth) / canvas.width;

    pdf.addImage(imgData, 'PNG', 0, 10, pageWidth, pageHeight);
    pdf.save('teacher_timetable.pdf');

    pdfContent.classList.remove('pdf-style');
}

function printTimetable(button) {
    const timetableData = JSON.parse(button.getAttribute("data-timetable"));
    const courseName = button.getAttribute("data-course");
    const ntaLevel = button.getAttribute("data-nta");
    const groupName = button.getAttribute("data-group");
    const semester = button.getAttribute("data-semester");
    const active = button.getAttribute("data-active");

    let timeslots = [];
    for (let day in timetableData) {
        timetableData[day].forEach(e => {
            const slot = `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}`;
            if (!timeslots.includes(slot)) timeslots.push(slot);
        });
    }
    timeslots.sort((a,b) => a.split(' - ')[0].localeCompare(b.split(' - ')[0]));
    const days = Object.keys(timetableData);

    const printWindow = window.open('', '', 'width=1200,height=900');
    printWindow.document.write(`
        <html>
        <head>
            <title>Class Timetable</title>
            <style>
                @page { margin: 0; }
                body { margin: 40px; font-family: 'Times New Roman', Times, serif; color: black; }
                h2,h4,h5 { text-align: center; margin: 2px 0; line-height: 1.4; }
                table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 25px; }
                th, td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
                th { background-color: #e9ecef; text-transform: uppercase; font-weight: bold; }
                td:first-child { font-weight: bold; background-color: #f2f2f2; text-transform: uppercase; }
                tr:nth-child(even) { background: #f8f9fa; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            <h2>THE INSTITUTE OF PUBLIC AND ADMINISTRATION</h2>
            <h4>${courseName.toUpperCase()} - ${ntaLevel.toUpperCase()}</h4>
            ${groupName ? `<h5>GROUP: ${groupName.toUpperCase()}</h5>` : ""}
            <h5>TIMETABLE FOR ${semester.toUpperCase()}: ${active.toUpperCase()}</h5>

            <table>
                <thead>
                    <tr>
                        <th>DAY / TIME</th>
                        ${timeslots.map(slot => `<th>${slot}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${days.map(day => `
                        <tr>
                            <td>${day.toUpperCase()}</td>
                            ${timeslots.map(slot => {
                                const entry = timetableData[day].find(e =>
                                    `${e.start_time.slice(0,5)} - ${e.end_time.slice(0,5)}` === slot
                                );
                                return entry ? `<td><strong>${entry.subjectName}</strong><br>${entry.firstname} ${entry.lastname}<br>${entry.room_name}</td>` : `<td></td>`;
                            }).join('')}
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>


</body>
</html>
