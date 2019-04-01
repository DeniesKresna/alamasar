<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Koordinator</th>
        <th>Sponsor</th>
        <th>Siswa</th>
        <th>Tahun Ajaran</th>
        <th>Jumlah</th>
        <th>Waktu Donasi</th>
        <th>Pengedit Terakhir</th>
        <th>Waktu Edit</th>
    </tr>
    </thead>
    <tbody>
    @php $i=0; @endphp
    @foreach($donates as $donate)
    @php $i++; @endphp
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $donate->coordinator->name }}</td>
            <td>{{ $donate->sponsor->name }}</td>
            <td>{{ $donate->student->name }}</td>
            <td><ul>@foreach($donate->years as $year)
             <li>{{ $year->year }};</li>  
             @endforeach</ul></td>
            <td>{{ $donate->amount }}</td>
            <td>{{ $donate->send_time }}</td>
            <td>{{ $donate->pengupdate->name }}</td>
            <td>{{ $donate->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>