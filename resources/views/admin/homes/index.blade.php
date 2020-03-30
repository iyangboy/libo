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
    <div class="search">
      <form class="form-inline" action="{{route('admin.home')}}">
        <div class="form-group">
          <label for="exampleInputName2">开始时间</label>
        <input type="date" class="form-control" id="startTime" name="start_time" value="{{$request->start_time ?? ''}}" placeholder="开始时间">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail2">结束时间</label>
          <input type="date" class="form-control" id="endTime" name="end_time" value="{{$request->end_time ?? ''}}" placeholder="结束时间">
        </div>
        <button type="submit" class="btn btn-default">确认</button>
      </form>
    </div>
    <hr>
    <table class="table table-bordered">
      <tbody>
        <tr>
          <td colspan="5"><b>用户数据</b></td>
        </tr>
        <tr>
          <td>UV</td>
          <td>注册总用户数</td>
          <td>会员人数</td>
          <td>身份证登记人数</td>
          <td>基本信息填写人数</td>
          <td>绑卡成功数</td>
        </tr>
        <tr>
          <td></td>
          <td>{{ $user_count ?? '' }}</td>
          <td>{{ $user_grade_count ?? ''}}</td>
          <td>{{ $user_id_card_count ?? ''}}</td>
          <td>{{ $user_info_count ?? ''}}</td>
          <td>{{ $user_bank_card_count ?? ''}}</td>
        </tr>
        @foreach($sources as $key => $value)
        <tr>
          <td colspan="6"><b>来源-> {{ $value->name }}</b> [所属管理员: {{ $value->adminUser->name ?? '未分配'}}]</td>
        </tr>
        <tr>
          <td>{{ $value->uv()->count() ?? '' }}</td>
          <td>{{ $value->users()->count() ?? '' }}</td>
          <td>{{ $value->users()->where('grade_id', '>', 0)->count() ?? ''}}</td>
          <td>{{ $value->users()->where('id_card', '>', 0)->count() ?? ''}}</td>
          @php
          $source_user_info = \App\Models\User::query()->whereHas('userInfo', function
          (\Illuminate\Database\Eloquent\Builder $query) {
            $query->where('user_id', '>', 0);
          })->where('source_id', $value->id);
          if ($search = ($request->start_time ?? '')) {
            $source_user_info->where('created_at', '>', $search);
          }
          if ($search = ($request->end_time ?? '')) {
            $source_user_info->where('created_at', '<', $search);
          }
          $source_user_info_count = $source_user_info->count();
          @endphp
          <td>{{ $source_user_info_count ?? ''}}</td>
          @php
          $source_user_bank_card = \App\Models\User::whereHas('userBankCards', function
          (\Illuminate\Database\Eloquent\Builder $query) {
          $query->where('protocol_id', '>', 0);
          })->where('source_id', $value->id);
          if ($search = ($request->start_time ?? '')) {
            $source_user_bank_card->where('created_at', '>', $search);
          }
          if ($search = ($request->end_time ?? '')) {
            $source_user_bank_card->where('created_at', '<', $search);
          }
          $source_user_bank_card_count = $source_user_bank_card->count();
          @endphp
          <td>{{ $source_user_bank_card_count ?? ''}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{-- <table class="table table-bordered">
      <tr>
      <td width="20%"><canvas id="myChart" width="400" height="400"
        data-user_statistics_key="{{$user_statistics_key}}"
        data-user_statistics_value="{{$user_statistics_value}}"
        ></canvas></td>
      <td width="20%"></td>
        <td width="20%"></td>
        <td width="20%"></td>
        <td width="20%"></td>
      </tr>
    </table> --}}
    <div></div>
  </div>
</div>

<script>
  $(document).ready(function () {

  });
  // $(function () {
  //   var ctx = document.getElementById("myChart").getContext('2d');
  //   var user_statistics_key = $("#myChart").data('user_statistics_key');
  //   var user_statistics_value = $("#myChart").data('user_statistics_value');
  //   var myChart = new Chart(ctx, {
  //     type: 'bar',
  //     data: {
  //       // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
  //       labels: user_statistics_key,
  //       datasets: [{
  //         label: '近七天注册量',
  //         // data: [12, 19, 3, 5, 2, 3, 7],
  //         data: user_statistics_value,
  //         backgroundColor: [
  //           'rgba(255, 99, 132, 0.2)',
  //           'rgba(54, 162, 235, 0.2)',
  //           'rgba(255, 206, 86, 0.2)',
  //           'rgba(75, 192, 192, 0.2)',
  //           'rgba(153, 102, 255, 0.2)',
  //           'rgba(153, 102, 255, 0.2)',
  //           'rgba(255, 159, 64, 0.2)'
  //         ],
  //         borderColor: [
  //           'rgba(255,99,132,1)',
  //           'rgba(54, 162, 235, 1)',
  //           'rgba(255, 206, 86, 1)',
  //           'rgba(75, 192, 192, 1)',
  //           'rgba(153, 102, 255, 1)',
  //           'rgba(153, 102, 255, 1)',
  //           'rgba(255, 159, 64, 1)'
  //         ],
  //         borderWidth: 1
  //       }]
  //     },
  //     options: {
  //       scales: {
  //         yAxes: [{
  //           ticks: {
  //             beginAtZero: true
  //           }
  //         }]
  //       }
  //     }
  //   });
  // });

</script>
