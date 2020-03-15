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
          <td><b>用户数据</b></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>注册总用户数</td>
          <td>会员人数</td>
          <td>身份证登记人数</td>
          <td>基本信息填写人数</td>
        </tr>
        <tr>
          <td>{{ $user_count ?? '' }}</td>
          <td>{{ $user_grade_count ?? ''}}</td>
          <td>{{ $user_id_card_count ?? ''}}</td>
          <td>{{ $user_info_count ?? ''}}</td>
        </tr>
        {{-- <tr>
          <td>身份证号：</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>联系方式：</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>来源：</td>
          <td><span class="label label-success"></span></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="4">绑卡信息：</td>
        </tr>
        <tr>
          <td>用户：</td>
          <td>银行</td>
          <td>卡号</td>
          <td>手机号</td>
        </tr> --}}
        {{-- @foreach($user->userBankCards as $key => $value)
      <tr>
        <td>{{ $value->user_name }}</td>
        <td>{{ $value->bank_name }}</td>
        <td>{{ $value->card_number }}</td>
        <td>{{ $value->phone }}</td>
        </tr>
        @endforeach --}}
        {{-- <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td>地址信息：</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>职业：</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>手机入网时长：</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>网贷逾期情况:</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>社保:</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>公积金:</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>月薪:</td>
          <td></td>
          <td></td>
          <td></td>
        </tr> --}}
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function () {

  });

</script>
