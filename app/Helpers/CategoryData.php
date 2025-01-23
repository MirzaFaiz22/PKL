<?php

namespace App\Helpers;

class CategoryData
{
    public static function getCategoryData()
    {
        return [
            [
                'id' => '100643',
                'name' => 'Books & Magazines',
                'parentId' => '0',
                'children' => [
                    [
                        'id' => '100777',
                        'name' => 'Books',
                        'parentId' => '100643',
                        'children' => [
                            [
                                'id' => '101551',
                                'name' => 'Language Learning & Dictionaries',
                                'parentId' => '100777',
                                'children' => [],
                            ],
                            [
                                'id' => '101560',
                                'name' => 'Science & Maths',
                                'parentId' => '100777',
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => '100778',
                        'name' => 'E-Books',
                        'parentId' => '100643',
                        'children' => [],
                    ],
                    [
                        'id' => '100779',
                        'name' => 'Others',
                        'parentId' => '100643',
                        'children' => [],
                    ],
                ],
            ],
        ];
    }

    public static function findCategoryName($id, $categories = null)
    {
        if ($categories === null) {
            $categories = self::getCategoryData();
        }

        foreach ($categories as $category) {
            if ($category['id'] === $id) return $category['name'];
            
            if (!empty($category['children'])) {
                foreach ($category['children'] as $child) {
                    if ($child['id'] === $id) return $child['name'];
                    
                    if (!empty($child['children'])) {
                        foreach ($child['children'] as $grandchild) {
                            if ($grandchild['id'] === $id) return $grandchild['name'];
                        }
                    }
                }
            }
        }
        return 'Unknown Category';
    }
}