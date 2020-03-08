<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">用户详情：{{ $user->id }}</h3>
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
        <td>头像：</td>
        <td><img src="{{ $user->avatar }}" alt="" width="100"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>昵称：</td>
        <td>{{ $user->name }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>真实姓名：</td>
        <td>{{ $user->real_name }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>身份证号：</td>
        <td>{{ $user->id_card }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>联系方式：</td>
        <td>{{ $user->phone }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>来源：</td>
        <td><span class="label label-success">{{ $user->source->name ?? '' }}</span></td>
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
      </tr>
      @foreach($user->userBankCards as $key => $value)
      <tr>
        <td>{{ $value->user_name }}</td>
        <td>{{ $value->bank_name }}</td>
        <td>{{ $value->card_number }}</td>
        <td>{{ $value->phone }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td>地址信息：</td>
        <td>{{ $user->userInfo->full_address ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>职业：</td>
        <td>{{ $user->userInfo->occupation ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>手机入网时长：</td>
        <td>{{ $user->userInfo->phone_long_time ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>网贷逾期情况:</td>
        <td>{{ $user->userInfo->overdue_state ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>社保:</td>
        <td>{{ $user->userInfo->social_insurance ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>公积金:</td>
        <td>{{ $user->userInfo->accumulation_fund ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>月薪:</td>
        <td>{{ $user->userInfo->monthly_pay ?? '' }}</td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  </div>
</div>

<script>
$(document).ready(function() {

});
</script>
