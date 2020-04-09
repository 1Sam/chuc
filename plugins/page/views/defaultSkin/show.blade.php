{{ XeFrontend::css('plugins/page/assets/css/page.css')->load() }}
<!-- xe.js파일을 body의 상단에 로드함. 'body or head' -->
{{ XeFrontend::js('plugins/page/view/summonerinfo.js')->prependTo('body')->load() }}


{!! compile($pageId, $content, true) !!}

{{--dd(auth()->user())--}}

@php
$displayName = '';
if(auth()->check()) {
        $displayName = auth()->user()->getDisplayName();
} else {
        echo( '!! 로그인 상태가 아닙니다. 회원 가입하시고 로그인하시면 자신의 내전 기록을 볼 수 있습니다.');
        $displayName = '추 C';
};

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//시즌 정하기 &sheet=season13
//curl_setopt($ch, CURLOPT_URL, 'https://docs.google.com/spreadsheets/u/1/d/1_IStpN2QyCHOvnQxvbJslUdLnZOL2a4VNjnecCdKdHw/gviz/tq?tqx=out:json&tq=select+B+where+B+contains+%22%EB%8B%A4%20%EC%84%A0%22&sheet=season13');
$summoner = urlencode($displayName);
//$summoner = urlencode('옴히야 사랑해');
$summoner_url = "https://docs.google.com/spreadsheets/u/1/d/1_IStpN2QyCHOvnQxvbJslUdLnZOL2a4VNjnecCdKdHw/gviz/tq?tqx=out:json&tq=select+*+where+B+=+'{$summoner}'&sheet=season14";

curl_setopt($ch, CURLOPT_URL, $summoner_url);
$result = curl_exec($ch);
$result = preg_replace('#\/\*O_o\*\/|[\r\n]|google\.visualization\.Query\.setResponse\(|\);$#', '', $result);

$obj = json_decode($result);


// 메달 값
$history_url = "https://docs.google.com/spreadsheets/u/1/d/1ZPLvEoZ19aO-soATBK_PcDc0cnEUGHleU26VoIA8Sdc/gviz/tq?tqx=out:json&tq=select+*+where+C+=+'{$summoner}'&sheet=history&key=AIzaSyCu-ZvMSeO5OJ2GbXZi6CAuFxTzMX3erTk";

curl_setopt($ch, CURLOPT_URL, $history_url);
$result2 = curl_exec($ch);
$result2 = preg_replace('#\/\*O_o\*\/|[\r\n]|google\.visualization\.Query\.setResponse\(|\);$#', '', $result2);

$obj2 = json_decode($result2);

// 게임 이력
$matches_url = "https://docs.google.com/spreadsheets/u/1/d/1sTYa2EwecwvZBY2w5WPVyA0bFeVpIaSWf_euG-oG_i8/gviz/tq?tqx=out:json&tq=select+*+where+A+=+'season14'+and+D+!='BR'+and+(+E+=+'{$summoner}'+or+F+=+'{$summoner}'+or+G+=+'{$summoner}'+or+H+=+'{$summoner}'+or+I+=+'{$summoner}'+or+J+=+'{$summoner}'+or+K+=+'{$summoner}'+or+L+=+'{$summoner}'+or+M+=+'{$summoner}'+or+N+=+'{$summoner}'+)&sheet=matches";

curl_setopt($ch, CURLOPT_URL, $matches_url);
$result3 = curl_exec($ch);
$result3 = preg_replace('#\/\*O_o\*\/|[\r\n]|google\.visualization\.Query\.setResponse\(|\);$#', '', $result3);

$obj3 = json_decode($result3);



curl_close($ch);
@endphp
{{--dd($obj)--}}


<div class="summonorInfo">
    <!-- 여기에 innerHTML 값 덮어주면 됨 -->
</div>


<div class="divTable">
    <div class="divTableBody">
    <div class="divTableRow">

    @if (!empty($obj->table->rows[0]))
    <div class="divTableCell">&nbsp;소환사명</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[1]->v))
            {{$obj->table->rows[0]->c[1]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;이미지</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[2]->v))
        <img src="http://profile.img.afreecatv.com/LOGO/{{substr($obj->table->rows[0]->c[2]->v, 0, 2)}}/{{$obj->table->rows[0]->c[2]->v}}/{{$obj->table->rows[0]->c[2]->v}}.jpg">
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
        <div class="divTableCell">&nbsp;메달</div>
        <div class="divTableCell">&nbsp;
            @if (!empty($obj2->table->rows[0]))
                @foreach($obj2->table->rows as $item)
                    <!--img src="{{asset('images/season{$item->c[0]->v}_{$item->c[1]->v}.png')}}"-->
                    
                    <img src="{{ asset("plugins/page/assets/img/images/season{$item->c[0]->v}_{$item->c[1]->v}.png") }}">
                    
                    @endforeach
            @endif
        </div>
        <div class="divTableCell">&nbsp;</div>
        </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;아프리카닉네임</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[3]->v))
            {{$obj->table->rows[0]->c[3]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;라인별 티어와 숙련도</div>
    <div class="divTableCell">&nbsp;
        @php
           if (!empty($obj->table->rows[0]->c[4]->v)) {
                //echo $obj->table->rows[0]->c[4]->v;
                preg_match_all('/([가-힣]*[^abcde]\d?)([abcde]?)[,]?/', $obj->table->rows[0]->c[4]->v, $matches);
                if (count($matches)) {
                    echo $matches[1][0].'(탑'.$matches[2][0].') '.$matches[1][1].'(정글'.$matches[2][1].') '.$matches[1][2].'(미드'.$matches[2][2].') '.$matches[1][3].'(원딜'.$matches[2][3].') '.$matches[1][4].'(서폿'.$matches[2][4].')';
                }
           }
        @endphp
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;라인</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[5]->v))
            {{$obj->table->rows[0]->c[5]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;참전회수</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[6]->v))
            {{$obj->table->rows[0]->c[6]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;승점</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[7]->v))
            {{$obj->table->rows[0]->c[7]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;선참권</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[9]->v))
            {{$obj->table->rows[0]->c[9]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;스킨박스</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[11]->v))
            {{$obj->table->rows[0]->c[11]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;경고</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[13]->v))
            {{$obj->table->rows[0]->c[13]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;팀 이름</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[15]->v))
            {{$obj->table->rows[0]->c[15]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
    <div class="divTableCell">&nbsp;라인 아이디</div>
    <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[16]->v))
            {{$obj->table->rows[0]->c[16]->v}}
        @endif
    </div>
    <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
        <div class="divTableCell">&nbsp;카카오톡 아이디</div>
        <div class="divTableCell">&nbsp;
            @if (!empty($obj->table->rows[0]->c[17]->v))
                {{$obj->table->rows[0]->c[17]->v}}
            @endif
        </div>
        <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
        <div class="divTableCell">&nbsp;주장</div>
        <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[18]->v))
            {{$obj->table->rows[0]->c[18]->v}}
        @endif
        </div>
        <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
        <div class="divTableCell">&nbsp;오더</div>
        <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[19]->v))
            {{$obj->table->rows[0]->c[19]->v}}
        @endif
        </div>
        <div class="divTableCell">&nbsp;</div>
    </div>
    <div class="divTableRow">
        <div class="divTableCell">&nbsp;마스코트</div>
        <div class="divTableCell">&nbsp;
        @if (!empty($obj->table->rows[0]->c[20]->v))
            {{$obj->table->rows[0]->c[20]->v}}
        @endif
        </div>
        <div class="divTableCell">&nbsp;</div>
    </div>

    @else
        <div>{{$displayName}} 검색결과 없음</div>
    @endif
    </div>
    </div>
    <!-- DivTable.com -->

<style>
    /* DivTable.com */
.divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}
</style>


<div class="divTable" style="table-layout:fixed">
    <div class="divTableBody">
        <div class="divTableRow">
            <div class="divTableCell" style="width:7%">시즌</div>
            <div class="divTableCell" style="width:3%">일차</div>
            <div class="divTableCell" style="width:3%">승패</div>
            <div class="divTableCell">탑</div>
            <div class="divTableCell">정글</div>
            <div class="divTableCell">미드</div>
            <div class="divTableCell">원딜</div>
            <div class="divTableCell">서폿</div>
            <div class="divTableCell">탑</div>
            <div class="divTableCell">정글</div>
            <div class="divTableCell">미드</div>
            <div class="divTableCell">원딜</div>
            <div class="divTableCell">서폿</div>
        </div>

        @if (!empty($obj3->table->rows[0]))
            @foreach($obj3->table->rows as $item)
            <div class="divTableRow">
                <div class="divTableCell">{{$item->c[0]->v}}</div>
                <div class="divTableCell">{{$item->c[1]->v}}</div>
                <div class="divTableCell">{{$item->c[3]->v}}</div>
                <div class="divTableCell" {{ ($item->c[4]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[4]->v}}<br>{{$item->c[14]->v}}</div>
                <div class="divTableCell" {{ ($item->c[5]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[5]->v}}<br>{{$item->c[15]->v}}</div>
                <div class="divTableCell" {{ ($item->c[6]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[6]->v}}<br>{{$item->c[16]->v}}</div>
                <div class="divTableCell" {{ ($item->c[7]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[7]->v}}<br>{{$item->c[17]->v}}</div>
                <div class="divTableCell" {{ ($item->c[8]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[8]->v}}<br>{{$item->c[18]->v}}</div>
                <div class="divTableCell" {{ ($item->c[9]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[9]->v}}<br>{{$item->c[19]->v}}</div>
                <div class="divTableCell" {{ ($item->c[10]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[10]->v}}<br>{{$item->c[20]->v}}</div>
                <div class="divTableCell" {{ ($item->c[11]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[11]->v}}<br>{{$item->c[21]->v}}</div>
                <div class="divTableCell" {{ ($item->c[12]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[12]->v}}<br>{{$item->c[22]->v}}</div>
                <div class="divTableCell" {{ ($item->c[13]->v == $displayName ? 'style=background-color:yellow' : '')}}>{{ $item->c[13]->v}}<br>{{$item->c[23]->v}}</div>
            </div>
            @endforeach
        @endif

    </div>
</div>


@if ($obj->status = 'ok')
    <div>Status : {{$obj->status}} </div>
    

    <div><!--소환사명 :--> {{--$obj->table->rows[0]->c[1]->v--}}
        {{--dd($obj)--}}
    {{--$obj->table->rows[0]->c[0]->v--}}
    </div>
@else
    <div>Status : {{dd($obj->status)}} </div>
    <div>obj : {{dd($obj)}}</div>
    
@endif


{{--$result)--}}



@if(Auth::check() && in_array(Auth::user()->rating, ['super', 'manager']))
    <a class="xe-btn xe-btn-default" href="{!! route('manage.plugin.page.edit', $pageId) !!}">{{xe_trans('xe::goSettingPage')}}</a>
@endif

@if ($config->get('comment') === true)
    <div class="__xe_comment board_comment">
        {!! uio('comment', ['target' => $pageCommentTarget]) !!}
    </div>
@endif
