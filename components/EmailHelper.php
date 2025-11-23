<?php
namespace app\components;

use Yii;

class EmailHelper
{
    /**
     * Kirim email notifikasi ROP
     * 
     * @param array $data [
     *     'stockRop' => StockRop model,
     *     'barang' => Barang model,
     *     'currentStock' => float
     * ]
     * @return bool
     */
    public static function sendRopNotification($data)
    {
        try {
            $barang = $data['barang'];
            $stockRop = $data['stockRop'];
            $currentStock = $data['currentStock'];
            
            // Validasi data
            if (!$barang || !$stockRop) {
                Yii::error("Invalid data for ROP notification", __METHOD__);
                return false;
            }
            
            // Kirim email
            $result = Yii::$app->mailer->compose(
                ['html' => 'rop-notification'], // Template name
                [
                    'barang' => $barang,
                    'stockRop' => $stockRop,
                    'currentStock' => $currentStock,
                    'deficit' => $stockRop->jumlah_rop - $currentStock,
                    'recommendedOrder' => $stockRop->jumlah_eoq,
                ]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo(Yii::$app->params['ropNotificationEmails'])
            ->setSubject('!! URGENT: Stock Barang Menipis - ' . $barang->nama_barang)
            ->send();
            
            if ($result) {
                Yii::info("ROP notification email sent for barang_id: {$barang->barang_id}", __METHOD__);
                return true;
            } else {
                Yii::warning("ROP notification email failed to send", __METHOD__);
                return false;
            }
            
        } catch (\Exception $e) {
            Yii::error("Failed to send ROP notification email: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * Kirim email digest untuk multiple barang
     * 
     * @param array $stockRopList Array of [stockRop, barang, currentStock]
     * @return bool
     */
    public static function sendRopDigestNotification($stockRopList)
    {
        try {
            if (empty($stockRopList)) {
                return false;
            }
            
            $result = Yii::$app->mailer->compose(
                ['html' => 'rop-digest'],
                ['stockRopList' => $stockRopList]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo(Yii::$app->params['ropNotificationEmails'])
            ->setSubject('Daily ROP Alert - ' . count($stockRopList) . ' Barang Perlu Dipesan')
            ->send();
            
            if ($result) {
                Yii::info("ROP digest email sent for " . count($stockRopList) . " items", __METHOD__);
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Yii::error("Failed to send ROP digest email: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}