import React from 'react';
import { Item } from '@/types';

export default function Show({ item }: {item: Item}) {
    return (
        <div className='flex flex-col bg-gray-600'>
            <div>name: {item.name}</div>
            <div>memo: {item.memo}</div>
        </div>
    )
}
