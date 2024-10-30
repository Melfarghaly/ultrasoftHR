<?php

namespace Modules\HRAdvanced\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HRAdvanced\Entities\SalaryItem;

class ItemSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        
            $items = [
                ['item_name' => 'أساسي', 'item_type' => 'earning'],
                ['item_name' => 'بدل سكن', 'item_type' => 'earning'],
                ['item_name' => 'ب نقل', 'item_type' => 'earning'],
                ['item_name' => 'إكرامية', 'item_type' => 'earning'],
                ['item_name' => 'بدل طبيعة عمل', 'item_type' => 'earning'],
                ['item_name' => 'بدل محرقات', 'item_type' => 'earning'],
                ['item_name' => 'ب طعام', 'item_type' => 'earning'],
                ['item_name' => 'ب جوال', 'item_type' => 'earning'],
                ['item_name' => 'ب صندوق', 'item_type' => 'earning'],
                ['item_name' => 'ب إدارية', 'item_type' => 'earning'],
                ['item_name' => 'ب إدارة سكن', 'item_type' => 'earning'],
                ['item_name' => 'ب تأمين طبي', 'item_type' => 'earning'],
                ['item_name' => 'ب تذاكر', 'item_type' => 'earning'],
                ['item_name' => 'ب إجازة', 'item_type' => 'earning'],
                ['item_name' => 'ب خدمة', 'item_type' => 'earning'],
                ['item_name' => 'بدلات أخرى', 'item_type' => 'earning'],
                ['item_name' => 'بدل سكن كل 6 شهور', 'item_type' => 'earning'],
                ['item_name' => 'بدل سكن سنوي', 'item_type' => 'earning'],
                ['item_name' => 'إجمالي بدل السكن', 'item_type' => 'earning'],
                ['item_name' => 'قيمة إضافي', 'item_type' => 'earning'],
                ['item_name' => 'قيمة إضافي التكثيف', 'item_type' => 'earning'],
                ['item_name' => 'قيمه ساعات الخصم', 'item_type' => 'deduction'],
                ['item_name' => 'بدلات أخرى المالية', 'item_type' => 'earning'],
                ['item_name' => 'تأمينات 9% + 0.75% ساند', 'item_type' => 'deduction'],
                ['item_name' => 'جزاء غياب', 'item_type' => 'deduction'],
                ['item_name' => 'جزاء مخالفات', 'item_type' => 'deduction'],
                ['item_name' => 'تسوية الجزاء الإداري', 'item_type' => 'deduction'],
                ['item_name' => 'سلفيات', 'item_type' => 'deduction'],
                ['item_name' => 'القسط التأميني', 'item_type' => 'deduction'],
                ['item_name' => 'سلفة نقدية', 'item_type' => 'deduction'],
                ['item_name' => 'تصديق وتوثيق', 'item_type' => 'deduction'],
                ['item_name' => 'إجمالي قيمة الإضافي', 'item_type' => 'earning'],
                ['item_name' => 'أساسي تأمينات', 'item_type' => 'earning'],
                ['item_name' => 'بدل سكن تأمينات', 'item_type' => 'earning'],
                ['item_name' => 'راتب التأمينات', 'item_type' => 'earning'],
                ['item_name' => 'إضافي ساعات', 'item_type' => 'earning'],
                ['item_name' => 'ساعات إضافي التكثيف', 'item_type' => 'earning'],
                ['item_name' => 'ساعات الخصم', 'item_type' => 'deduction'],
                ['item_name' => 'إجمالي ساعات إضافي', 'item_type' => 'earning'],
                ['item_name' => 'أيام العمل', 'item_type' => 'earning'],
                ['item_name' => 'جزاء أيام', 'item_type' => 'deduction'],
                ['item_name' => 'أيام الجزاء الإداري', 'item_type' => 'deduction'],
                ['item_name' => 'جزاء قيمة', 'item_type' => 'deduction'],
                ['item_name' => 'عدد ساعات إضافي رمضان', 'item_type' => 'earning'],
                ['item_name' => 'عدد ساعات الإضافي الطاري', 'item_type' => 'earning'],
                ['item_name' => 'أساسي قياسي', 'item_type' => 'earning'],
                ['item_name' => 'عدد أيام الإجازة المرضي', 'item_type' => 'deduction'],
                ['item_name' => 'خصم الإجازة المرضي', 'item_type' => 'deduction'],
                ['item_name' => 'صافي الراتب', 'item_type' => 'earning'],
                ['item_name' => 'إجمالي المدخولات', 'item_type' => 'earning'],
                ['item_name' => 'إجمالي الاستقطاعات', 'item_type' => 'deduction'],
                ['item_name' => 'إجمالي عقد العمل', 'item_type' => 'earning'],
                ['item_name' => 'إجمالي راتب المدينة', 'item_type' => 'earning'],
                ['item_name' => 'أخطار مهنية 2%', 'item_type' => 'deduction'],
                ['item_name' => 'معاشات ح شركة 9% + 0.75% ساند', 'item_type' => 'deduction'],
                ['item_name' => 'معاشات ح موظف 9% + 0.75% ساند', 'item_type' => 'deduction']
            ];
            $itemCode=100;
            foreach ($items as $item) {
                $item['item_code']=$itemCode;
                SalaryItem::create($item);
                $itemCode++;
            }
    }
}
