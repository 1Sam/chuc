{{ XeFrontend::css('plugins/page/assets/css/page.css')->load() }}

{!! compile($pageId, $content, true) !!}

@if(Auth::check() && in_array(Auth::user()->rating, ['super', 'manager']))
    <a class="xe-btn xe-btn-default" href="{!! route('manage.plugin.page.edit', $pageId) !!}">{{xe_trans('xe::goSettingPage')}}</a>
@endif

@if ($config->get('comment') === true)
    <div class="__xe_comment board_comment">
        {!! uio('comment', ['target' => $pageCommentTarget]) !!}
    </div>
@endif
