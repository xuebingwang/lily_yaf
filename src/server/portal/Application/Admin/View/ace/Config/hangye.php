<extend name="Public/base"/>

<block name="body">
    <div class="row">
        <div class="col-sm-12">
            <form action="" class="search-form">
                <label>工程名称
                    <input type="text" class="search-input" name="industry_name" value="{:I('industry_name')}" placeholder="请输入工程名称">
                </label>
                <label>承包类型
                    <select name="indestry_type" >
                        <option value="">所有</option>
                        <option value="EPC">总承包</option>
                        <option value="SBT">专业承包</option>
                    </select>
                </label>

                <label>
                    <button class="btn btn-sm btn-primary" type="button" id="search-btn" url="{:U('config/hangye')}">
                        <i class="icon-search"></i>搜索
                    </button>
                </label>
            </form>
        </div>
    </div>
    <!-- 数据列表 -->
    <div class="table-responsive">
        <div class="dataTables_wrapper">  

            <include file="Think/lists_common"/>
            <div class="row">
                <div class="col-sm-4">
                    <label>
                        <a href="<?=U('config/addhangye')?>" class="btn btn-white">
                            新增
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <include file="Public/page"/>
                </div>
            </div>
        </div>
    </div>
</block>