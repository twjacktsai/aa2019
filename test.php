<?

class pipelineController extends dbController {
    function testcAction() {

        ajaxreturn(array(
            "auth" => true,
            "app" => 'systemLogin',
            "header" => getallheaders()
        ));
    }
    function pipelineAction() {
        $ret = array();
        switch (get('METHOD')) {
            case 'GET':
                $datas = $this->db->fetchAll("select pid as opid,pipeline.* from pipeline where sales = ? order by utime desc", $_SESSION['user']);
                break;
            case 'PUT':
                $ori = $this->db->fetch("select * from pipeline where pid = ?", get('opid'));
                $data = array(
                    'sales' => $_SESSION['user'],
                    'dt' => get('dt'), //經銷商
                    'brand' => get('brand'), //brand
                    'dtemail' => get('dtemail'), //經銷商Email
                    'dtfax' => get('dtfax'), //經銷商傳真號碼
                    'dtphone' => get('dtphone'), //經銷商行動電話
                    'dtsale' => get('dtsale'), //經銷商業務
                    'dttel' => get('dttel'), //經銷商聯絡電話
                    'edt' => get('edt'), //經銷商(英)
                    'epjcust' => get('epjcust'), //客戶名稱英文
                    'pj' => get('pj'), //專案名稱
                    'pjamount' => get('pjamount'), //專案總金額
                    'pjbkdate' => get('pjbkdate'), //申請日期
                    'pjcontact' => get('pjcontact'), //承辦人
                    'pjcontent' => get('pjcontent'), //專案項目
                    'pjcttitle' => get('pjcttitle'), //承辦人職稱
                    'pjcust' => get('pjcust'), //客戶名稱
                    'pjcustaddr' => get('pjcustaddr'), //客戶住址
                    'pjemail' => get('pjemail'), //客戶 Email
                    'pjenddate' => format(get('pjenddate'), 'Y-m-d'), //預計結案日期
                    'pjendtype' => get('pjendtype'), //結案方式
                    'pjma' => get('pjma'), //MA (YR)
                    'pjnote' => get('pjnote'), //其它資訊
                    'pjphone' => get('pjphone'), //客戶聯絡電話
                    'pjunit' => get('pjunit'), //承辦單位
                    'pjwinrate' => get('pjwinrate'), //Win Rate %
                    'pjstatus' => get('pjstatus'), //Win Rate %
                    'closeflag' => get('closeflag')?1:0, //Close
                    'utime' => now()
                );
                $diff = array_diff((array)$ori, (array)$data);
                unset($diff['ctime']);
                unset($diff['utime']);
                unset($diff['pid']);
                $d = json_encode($diff, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                $ret = $this->db->query('update pipeline set', [
                    'sales' => $_SESSION['user'],
                    'dt' => get('dt'), //經銷商
                    'brand' => get('brand'), //brand
                    'dtemail' => get('dtemail'), //經銷商Email
                    'dtfax' => get('dtfax'), //經銷商傳真號碼
                    'dtphone' => get('dtphone'), //經銷商行動電話
                    'dtsale' => get('dtsale'), //經銷商業務
                    'dttel' => get('dttel'), //經銷商聯絡電話
                    'edt' => get('edt'), //經銷商(英)
                    'epjcust' => get('epjcust'), //客戶名稱英文
                    'pj' => get('pj'), //專案名稱
                    'pjamount' => get('pjamount'), //專案總金額
                    'pjbkdate' => get('pjbkdate'), //申請日期
                    'pjcontact' => get('pjcontact'), //承辦人
                    'pjcontent' => get('pjcontent'), //專案項目
                    'pjcttitle' => get('pjcttitle'), //承辦人職稱
                    'pjcust' => get('pjcust'), //客戶名稱
                    'pjcustaddr' => get('pjcustaddr'), //客戶住址
                    'pjemail' => get('pjemail'), //客戶 Email
                    'pjenddate' => get('pjenddate'), //預計結案日期
                    'pjendtype' => get('pjendtype'), //結案方式
                    'pjma' => get('pjma'), //MA (YR)
                    'pjnote' => get('pjnote'), //其它資訊
                    'pjphone' => get('pjphone'), //客戶聯絡電話
                    'pjunit' => get('pjunit'), //承辦單位
                    'pjwinrate' => get('pjwinrate'), //Win Rate %
                    'pjstatus' => get('pjstatus'), //Win Rate %
                    'closeflag' => get('closeflag')?1:0, //Close
                    'uoper' => $_SESSION['user'],
                    'utime' => now()
                ], 'WHERE pid = ?', get('opid'));

                if (count($diff) > 0) {
                    $ret = $this->db->query('insert into pipelineHistory %v', [
                        'pid' => get('opid'),
                        'history' => $d,
                        'uoper' => $_SESSION['user'],
                        'utime' => now()
                    ]);
                }
                if (isset($diff['pjenddate']) || isset($diff['pjstatus'])) {
                    $ret = $this->db->query('insert into pipelineChangeLog %v', [
                        'pid' => get('opid'),
                        'pjenddate' => $ori['pjenddate'],
                        'pjstatus' => $ori['pjstatus'],
                        'uoper' => $_SESSION['user'],
                        'utime' => $ori['utime']
                    ]);
                }


                break;
            case 'DELETE':
                $this->db->query("delete from pipeline where pid = ?", get('opid'));
                break;
            case 'POST':
                $ret = $this->db->query('insert into pipeline %v', [
                    'sales' => $_SESSION['user'],
                    'dt' => get('dt'), //經銷商
                    'brand' => get('brand'), //brand
                    'dtemail' => get('dtemail'), //經銷商Email
                    'dtfax' => get('dtfax'), //經銷商傳真號碼
                    'dtphone' => get('dtphone'), //經銷商行動電話
                    'dtsale' => get('dtsale'), //經銷商業務
                    'dttel' => get('dttel'), //經銷商聯絡電話
                    'edt' => get('edt'), //經銷商(英)
                    'epjcust' => get('epjcust'), //客戶名稱英文
                    'pj' => get('pj'), //專案名稱
                    'pjamount' => get('pjamount'), //專案總金額
                    'pjbkdate' => get('pjbkdate'), //申請日期
                    'pjcontact' => get('pjcontact'), //承辦人
                    'pjcontent' => get('pjcontent'), //專案項目
                    'pjcttitle' => get('pjcttitle'), //承辦人職稱
                    'pjcust' => get('pjcust'), //客戶名稱
                    'pjcustaddr' => get('pjcustaddr'), //客戶住址
                    'pjemail' => get('pjemail'), //客戶 Email
                    'pjenddate' => get('pjenddate'), //預計結案日期
                    'pjendtype' => get('pjendtype'), //結案方式
                    'pjma' => get('pjma'), //MA (YR)
                    'pjnote' => get('pjnote'), //其它資訊
                    'pjphone' => get('pjphone'), //客戶聯絡電話
                    'pjunit' => get('pjunit'), //承辦單位
                    'pjwinrate' => get('pjwinrate'), //Win Rate %
                    'coper' => $_SESSION['user'],
                    'ctime' => now()
                ]);
                $pid = $this->db->getInsertId();
                $datas = $this->db->fetchAll("select pid as opid,pipeline.* from pipeline where pid = ?", $pid);
                break;
        }

        ajaxreturn(array(
            "success" => true,
            "data" => $datas,
            "ret1" => dibi::$sql,
            "diff" => $diff,
            "d" => count($diff)
        ));
    }
}
