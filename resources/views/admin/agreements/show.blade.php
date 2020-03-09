<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">协议名称：{{ $agreement->title }}</h3>
    <div class="box-tools">
      <div class="btn-group float-right" style="margin-right: 10px">
        <a href="{{ url('admin/agreements') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <div class="contents">{!! $agreement->content !!}</div>
  </div>
</div>

<script>
  $(document).ready(function () {

  });

</script>
