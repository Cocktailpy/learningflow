<?php

/*
|--------------------------------------------------------------------------
| 内部公共调用接口
|--------------------------------------------------------------------------
 */

namespace xxxxxxxx;

class InternalController extends Controller
{
    public function downloadExport() {
        //导出excel
        if (!empty(request('export'))) {
            return (new \App\Services\ExportService())->handle($model_query, 'static');
        }

        if (request('export')) {
            return $data;
        }
    }

    /**
     * 下载系统导出文件
     * @author cloty
     * @datetime 2017-10-20T17:17:53+080
     * @version  1.0
     * @return   \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function downloadExportCsv()
    {
        $fileName = request('export_name');

        if (empty($fileName)) {
            return back()->with('error', '下载失败，文件不存在');
        }

        $exportService = app('export.csv');

        $exportService->setFileName($fileName);

        if (!$exportService->fileExist()) {
            return back()->with('error', '下载失败，文件不存在');
        }

        return $exportService->download();
    }
 
    public function simpleExportCsv()
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=download.csv");
        $output = fopen('php://output', 'w');

        $keys = ['id', 'created_at'];
        $column = [
            'id' => 'ID',
            'created_at' => '提交时间',
        ];

        $row = [];
        foreach ($keys as $k) {
            if (isset($column[$k])) {
                $row[] = mb_convert_encoding($column[$k], 'gb2312', 'utf8');
            } else {
                $row[] = $k;
            }
        }
        fputcsv($output, $row);

        foreach ($wenjuans as $w) {
            $row = [];
            foreach ($keys as $k) {
                if ($k == 'content' || $k == 'description') {
                    $getAttributes = strip_tags($w->getAttributes()[$k]);
                    $row[] = mb_convert_encoding($getAttributes, 'gb2312', 'utf8');
                } elseif ($k == 'imgurls') {
                    $getAttributes = implode(json_decode($w->getAttributes()[$k], true));
                    $row[] = mb_convert_encoding($getAttributes, 'gb2312', 'utf8');
                } else {
                    $row[] = mb_convert_encoding($w->getAttributes()[$k], 'gb2312', 'utf8');
                }

            }
            fputcsv($output, $row);
        }
        fclose($output);
        return;
    }
}
