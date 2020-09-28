<?php
/**
 * 积分管理模块
 * User: Hongfei
 * Date: 2019/1/17
 * Time: 下午1:48
 */

namespace app\index\model;

use think\Model;
use think\Log;
use think\Db;

class Integral extends Model
{
    /**
     * 获取积分列表
     * @param $param
     * @return array|\think\Paginator
     */
    public function integralList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('client')
                ->alias('u')
                ->field('u.id,u.name as userName,u.age,u.sex,g.name as groupName,t.name as tagName,u.integral')
                ->join(['v_client_group'=>'g'],'u.id=g.cid','LEFT')
                ->join(['v_client_tag'=>'t'],'u.id=t.cid','LEFT')
                ->order('u.id desc')
                ->where($where)
                ->paginate($limit,false,['query'=>$query]);
            return $data;
        }catch (\Exception $e){
            Log::write('获取积分列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 积分明细查询
     * @param array $where
     * @param $param
     * @return array|\think\Paginator
     */
    public function integralRecords($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('integral')->alias('i')
                ->field('u.id,u.name,u.sex,u.age,i.score,i.remarks,i.create_time,i.type')
                ->join('client u','i.uid=u.id','LEFT')
                ->where($where)
                ->order('i.id desc')
                ->paginate($limit, false, ['query'=>$query]);
            return $data;
        } catch (\Exception $e) {
            Log::write('积分明细记录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 变更积分
     * @param $param
     * @param $state=>状态 1为添加 2为核销
     * @return array
     */
    public function addInt($param,$state)
    {
        try {
            $validate = validate('Integral');
            if(!$validate->check($param)){
                return ['code'=>1,'msg'=>$validate->getError()];
            }
            if ($param['score'] < 0){
                return ['code'=>2,'msg'=>'变更的积分必须大于等于0'];
            }
            $data = [
                'uid'=>$param['uid'],
                'score'=>$param['score'],
                'state'=>$state,
                'type'=>$param['type'],
                'create_time'=>time(),
            ];
            $this->insert($data);
            if ($state == 1){
                Db::name("client")->where(['id'=>$param['uid']])->setInc('integral',$param['score']);

            }elseif($state == 2){
                Db::name("client")->where(['id'=>$param['uid']])->setDec('integral',$param['score']);
            }
            return ['code'=>0,'msg'=>'操作成功'];
        } catch (\Exception $e) {
            Log::write('变更积分异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 导出积分核销模板
     */
    public function exportTemplet()
    {
        try {
            // excel的header头
            $header = ['姓名', '身份证号码', '需核销的积分', '积分变化原因'];
            // 文件名
            $fileName = '积分核销模板';
            F_export_excel($data=[], $header, $key=[], $fileName);
        } catch (\Exception $e) {
            Log::write('核销模板导出异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 批量核销积分
     * @param string $file excel地址
     * @return array ['code'=>0,'msg'=>'核销结果']
     */
    public function batchDestoryIntegra($file)
    {
        try {
            set_time_limit(0);  // 防止excel数据过多执行时间长
            // 处理excel文件
            vendor('PHPExcel.PHPExcel.IOFactory');
            $excel = \PHPExcel_IOFactory::load($file,$encode = 'utf-8');
            $sheet = $excel->getSheet(0); // 获取表中第一个工作表 去除列名称所属行
            $excel_rows = $sheet->getHighestRow(); //取得总行数
            $insert_arr = [];   // 带插入的数组
            $update_arr = [];   // 需要更新的数组
            $time = time();
            for($i=2; $i<=$excel_rows; $i++){
                $name = $excel->getActiveSheet()->getCell("A" . $i)->getValue();        // 姓名
                $id_number = $excel->getActiveSheet()->getCell("B" .$i)->getValue();        // 身份证
                $score = $excel->getActiveSheet()->getCell("C" .$i)->getValue();       // 核销的积分
                $remarks = $excel->getActiveSheet()->getCell("D" .$i)->getValue();      // 核销备注
                // 获取对应的用户id
                $uid = Db::query("SELECT `id`,`integral` FROM yc_client WHERE `name`='".$name."' AND `id_number`='".$id_number."'");
                if(!$uid[0]['id']){
                    continue;
                }
                $insert_arr[$i] = [
                    'uid'=>$uid[0]['id'],
                    'score'=>(int)$score,
                    'type'=>2,
                    'remarks'=>addslashes($remarks),
                    'create_time'=>$time
                ];
                $update_arr[$i] = [
                    'id'=>$uid[0]['id'],
                    'integral'=>(float)$uid[0]['integral']-(float)$score
                ];
            }
            if(empty($insert_arr)){
                return ['code'=>2,'msg'=>'excel没有数据'];
            }
            Db::startTrans();
            // 插入核销记录
            $insert = Db::name('integral')->insertAll($insert_arr);
            // 更改对应的用户的积分
            $sql_update = "INSERT INTO yc_client (`id`,`integral`) VALUES ";
            foreach($update_arr as $value){
                $sql_update .= "(".$value['id'].",'".$value['integral']."'),";
            }
            $sql_update = rtrim($sql_update,',');
            $sql_update .= "ON DUPLICATE KEY UPDATE `integral`=VALUES(`integral`)";
            $update = Db::execute($sql_update);
            if($insert && $update){
                // 删除excel文件
                unlink($file);
                Db::commit();
                return ['code'=>0,'msg'=>'积分核销成功，共核销'.count($insert_arr).'条'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'积分核销失败'];
        } catch (\Exception $e) {
            Log::write('批量积分核销异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 统计用户的积分情况
     * @param int $uid 用户id
     * @return array
     */
    public function clientIntegral($uid=0)
    {
        try {
            $where = $uid ? " WHERE `id`=".$uid : "" ;
            $sql = "SELECT SUM(`integral`) AS integral,MAX(`integral`) AS max,MIN(`integral`) AS `min`,COUNT(`id`) AS total FROM yc_client ".$where;
            $integral = Db::query($sql);
            foreach($integral as &$value){
                $value['average'] = sprintf("%.1f",$value['integral']/$value['total']);
            }
            return $integral[0];
        } catch (\Exception $e) {
            Log::write('用户积分数统计异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取累计发放和累计核销的积分情况
     * @param int $uid 用户id
     * @return array
     */
    public function sumIntegralRecords($uid=0)
    {
        try {
            $where = $uid ? " WHERE `uid`=".$uid : "" ;
            $sql = "SELECT `type`,SUM(`score`) AS score,COUNT(`id`) AS total FROM yc_integral ".$where." GROUP BY `type`";
            $result = Db::query($sql);
            $score = [];
            foreach($result as $value){
                $type = $value['type'] == 1 ? "accumulate" : "destory" ;
                $score[$type]['score'] = $value['score'];
                $score[$type]['total'] = $value['total'];
            }
            return $score;
        } catch (\Exception $e) {
            Log::write('累计发放和核销总积分异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 批量发放积分
     * @param array $client 发放的服务对象信息数组
     * @param int $integral 发放的积分
     * @param string $remarks 积分内容
     * @return array ['code'=>0,'msg'=>'发放结果']
     */
    public function batchGrantIntegral($client, $integral, $remarks)
    {
        try {
            Db::startTrans();
            $ids = '';      // 服务对象的id组成的字符串，逗号隔开
            $time = time();
            $sql_insert = "INSERT INTO yc_integral (`uid`,`type`,`score`,`remarks`,`create_time`) VALUES ";
            foreach($client as $value){
                $ids .= $value['id'].",";
                $sql_insert .= "(".$value['id'].",1,".$integral.",'".$remarks."',".$time."),";
            }
            $sql_insert = rtrim($sql_insert,',');
            $insert = Db::execute($sql_insert);
            $ids = rtrim($ids, ',');
            $sql_update = "UPDATE yc_client SET `integral`=`integral`+".$integral." WHERE `id` IN (".$ids.")";
            $update = Db::execute($sql_update);
            if($insert && $update){
                Db::commit();
                return ['code'=>0,'msg'=>'积分发放成功'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'积分发放失败'];
        } catch (\Exception $e) {
            Log::write('批量发放积分异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}