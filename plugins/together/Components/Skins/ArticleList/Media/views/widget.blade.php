{{ XeFrontend::css('plugins/together/assets/css/widget.css')->load() }}
<section class="section-media-tfcw">
    <ul class="list-media-tfc reset-list">
        @foreach ($list as $idx => $item)

        @php
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $item->content, $match);
                //echo $youtube_id = $match[1];
                if(count($match) > 0) {
                    //dd($item->content,$match);
                    $youtube_thumb = 'https://img.youtube.com/vi/'.$match[1].'/0.jpg';
                }
        @endphp

            <li class="item-media">
                <a href="{{ $urlHandler->getShow($item) }}" class="link-media">
                    <span class="thumbnail" @if($item->thumb != null && $item->thumb->board_thumbnail_path) style="background-image:url('{{ $item->thumb->board_thumbnail_path }}')" @elseif($match != null) style="background-image:url('{{ $youtube_thumb }}')" @endif></span>
                    <strong class="title-media">{{ $item->title }}</strong>
                    <div class="box-view">
                        <span class="count">조회수 {{ $item->read_count }}</span>
                        <span class="date" data-xe-timeago="{{ $item->created_at }}" title="{{ $item->created_at }}">{{ $item->created_at }}</span>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</section>
