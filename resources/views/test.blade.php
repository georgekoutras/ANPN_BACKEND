{{ $test }}
<table>
    @foreach ($test as $index => $row)
        <tr>
            <td> $row['test']</td>
        </tr>
    @endforeach
</table>
