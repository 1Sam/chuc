{{ XeFrontend::css('plugins/together/assets/css/widget.css')->load() }}
<section class="section-media-tfcw">
    <ul class="list-media-tfc reset-list">
        @foreach ($list as $idx => $item)
            <li class="item-media">
                <a href="{{ $urlHandler->getShow($item) }}" class="link-media">
                    <span class="thumbnail"@if($item->thumb != null && $item->thumb->board_thumbnail_path) style="background-image:url('{{ $item->thumb->board_thumbnail_path }}')" @endif></span>
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
