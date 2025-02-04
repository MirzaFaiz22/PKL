<?php

namespace App\Helpers;

class CategoryData
{
    public static function getProductCategories()
    {
        return [
            [
                'id' => "100643",
                'name' => "Books & Magazines",
                'parentId' => "0",
                'children' => [
                    [
                        'id' => "100777",
                        'name' => "Books",
                        'parentId' => "100643",
                        'children' => [
                            [
                                'id' => "101551",
                                'name' => "Language Learning & Dictionaries",
                                'parentId' => "100777",
                                'children' => [],
                            ],
                            [
                                'id' => "101560",
                                'name' => "Science & Maths",
                                'parentId' => "100777",
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => "100778",
                        'name' => "E-Books",
                        'parentId' => "100643",
                        'children' => [],
                    ],
                    [
                        'id' => "100779",
                        'name' => "Others",
                        'parentId' => "100643",
                        'children' => [],
                    ],
                ],
            ],
        ];
    }

    public static function findCategoryName($id, $categories = null)
    {
        if ($categories === null) {
            $categories = self::getProductCategories();
        }

        // Flatten the category hierarchy and search
        $flatCategories = self::flattenCategories($categories);
        
        // Find the category by ID
        foreach ($flatCategories as $category) {
            if ($category['id'] === $id) {
                return $category['name'];
            }
        }

        return 'Unknown Category';
    }

    private static function flattenCategories($categories)
    {
        $result = [];
        
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name']
            ];

            if (!empty($category['children'])) {
                $result = array_merge($result, self::flattenCategories($category['children']));
            }
        }

        return $result;
    }

    public static function getProductCategoryOptions()
    {
        $categories = self::getProductCategories();
        return self::buildCategoryOptions($categories);
    }

    private static function buildCategoryOptions($categories, $prefix = '')
    {
        $options = [];

        foreach ($categories as $category) {
            $options[$category['id']] = $prefix . $category['name'];

            if (!empty($category['children'])) {
                $options += self::buildCategoryOptions(
                    $category['children'], 
                    $prefix . $category['name'] . ' > '
                );
            }
        }

        return $options;
    }
}