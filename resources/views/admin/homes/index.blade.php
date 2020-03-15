<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title"></h3>
    <div class="box-tools">
      <div class="btn-group float-right" style="margin-right: 10px">
        <a href="{{ url('admin/users') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
        <tr>
          <td colspan="5"><b>用户数据</b></td>
        </tr>
        <tr>
          <td>注册总用户数</td>
          <td>会员人数</td>
          <td>身份证登记人数</td>
          <td>基本信息填写人数</td>
          <td>绑卡成功数</td>
        </tr>
        <tr>
          <td>{{ $user_count ?? '' }}</td>
          <td>{{ $user_grade_count ?? ''}}</td>
          <td>{{ $user_id_card_count ?? ''}}</td>
          <td>{{ $user_info_count ?? ''}}</td>
          <td>{{ $user_bank_card_count ?? ''}}</td>
        </tr>
        @foreach($sources as $key => $value)
        <tr>
        <td colspan="5"><b>来源-> {{ $value->name }}</b> [所属管理员: {{ $value->adminUser->name ?? '未分配'}}]</td>
        </tr>
        <tr>
          <td>{{ $value->users()->count() ?? '' }}</td>
          <td>{{ $value->users()->where('grade_id', '>', 0)->count() ?? ''}}</td>
          <td>{{ $value->users()->where('id_card', '>', 0)->count() ?? ''}}</td>
          @php
            $source_user_info_count = \App\Models\User::whereHas('userInfo', function (\Illuminate\Database\Eloquent\Builder $query) {
              $query->where('user_id', '>', 0);
            })->where('source_id', $value->id)->count();
          @endphp
          <td>{{ $source_user_info_count ?? ''}}</td>
          @php
            $source_user_bank_card_count = \App\Models\User::whereHas('userBankCards', function (\Illuminate\Database\Eloquent\Builder $query) {
              $query->where('protocol_id', '>', 0);
            })->where('source_id', $value->id)->count();
          @endphp
          <td>{{ $source_user_bank_card_count ?? ''}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{-- <table class="table table-bordered">
      <tbody>
        <tr>
          <td colspan="5"><b>用户数据</b></td>
        </tr>
        <tr>
          <td>注册总用户数</td>
          <td>会员人数</td>
          <td>身份证登记人数</td>
          <td>基本信息填写人数</td>
          <td>绑卡成功数</td>
        </tr>
        <tr>
          <td>{{ $user_count ?? '' }}</td>
          <td>{{ $user_grade_count ?? ''}}</td>
          <td>{{ $user_id_card_count ?? ''}}</td>
          <td>{{ $user_info_count ?? ''}}</td>
          <td>{{ $user_bank_card_count ?? ''}}</td>
        </tr>
      </tbody>
    </table> --}}
  </div>
</div>

<script>
  $(document).ready(function () {

  });

</script>
