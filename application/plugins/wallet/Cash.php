<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\wallet;

use app\plugins\wallet\Common;
use app\plugins\wallet\service\CashService;
use app\plugins\wallet\service\BaseService;

/**
 * 钱包 - 余额提现
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Cash extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 余额提现
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        // 参数
        $params['user'] = $this->user;

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = BaseService::CashWhere($params);

        // 获取总数
        $total = BaseService::CashTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsHomeUrl('wallet', 'cash', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = BaseService::CashList($data_params);
        $this->assign('data_list', $data['data']);

        // 静态数据
        $this->assign('cash_status_list', CashService::$cash_status_list);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/cash/index');
    }

    /**
     * 余额提现 - 安全验证
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function authinfo($params = [])
    {
        // 手机号/邮箱
        $check_account_list = [
            ['field' => 'mobile_security', 'value' => 'mobile', 'name' => '手机'],
            ['field' => 'email_security', 'value' => 'email','name' => '邮箱'],
        ];
        $this->assign('check_account_list', $check_account_list);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/cash/authinfo');
    }

    /**
     * 余额提现 - 提现信息填写页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function createinfo($params = [])
    {
        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/wallet/cash/createinfo');
    }

    /**
     * 验证码显示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function verifyentry($params = [])
    {
        $params = array(
                'width' => 100,
                'height' => 28,
                'use_point_back' => false,
                'key_prefix' => 'wallet_cash',
            );
        $verify = new \base\Verify($params);
        $verify->Entry();
    }

    /**
     * 验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function verifysend($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params['user'] = $this->user;
        return CashService::VerifySend($params);
    }

    /**
     * 验证码校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function verifycheck($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params['user'] = $this->user;
        return CashService::VerifyCheck($params);
    }

    /**
     * 提现创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function create($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params['user'] = $this->user;
        return CashService::Create($params);
    }
}
?>