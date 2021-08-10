@extends('layout-app')

@section('title', '信用统计')

@section('content')
    <form class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2">
                <h4>个人项</h4>
            </label>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="kaoqin" class="col-sm-2 control-label">考勤</label>
            <div class="col-sm-4">
                <select id="kaoqin" name="kaoqin" class="form-control" data-for-tag="kaoqin">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
            <p class="col-sm-1 show-value" data-tag="kaoqin"></p>
        </div>

        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="gongzuo-jindu" class="col-sm-2 control-label">工作进度</label>
            <div class="col-sm-4">
                <select id="gongzuo-jindu" name="gongzuo-jindu" class="form-control">
                    <option value="1">提前 10%</option>
                    <option value="3">提前 20%</option>
                    <option value="10">提前 30%</option>
                    <option value="-1">延误 10%</option>
                    <option value="-3">延误 20%</option>
                    <option value="-10">延误 30%</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="gongzuo-taidu" class="col-sm-2 control-label">工作态度</label>
            <div class="col-sm-4">
                <select id="gongzuo-taidu" name="gongzuo-taidu" class="form-control">
                    <option value="-1">一般</option>
                    <option value="1">积极，主动</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">投票分</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2">
                <h4>团队项</h4>
            </label>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">乐于助人</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">生产质量</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">项目最多</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">质量</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-1">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">贡献</label>
            <div class="col-sm-4">
                <select id="toupiao-fen" name="toupiao-fen" class="form-control">
                    <option value="1">获得全勤奖</option>
                    <option value="-1">未获得全勤奖</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <p class="col-sm-2">&nbsp;</p>
        </div>
        <div class="form-group">
            <p class="col-sm-2">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">&nbsp;</label>
            <div class="col-sm-1">
                <button class="btn btn-primary form-control" type="submit" name="submit">提交</button>
            </div>
        </div>
        <div class="form-group">
            <p class="col-sm-2">&nbsp;</p>
        </div>
    </form>
    <script type="text/javascript">
        
    </script>
@endsection
