import { type ShoppingList } from '@/types';
import { Link } from '@inertiajs/react';

export default function ShoppingList({ shoppingList }: { shoppingList: ShoppingList }) {
    return (
        <div>
            <Link href={route('shopping_lists.index')} className="mt-4 inline-block text-blue-600 hover:text-blue-800 hover:underline">
                戻る
            </Link>
            
            <h1>{shoppingList.name}</h1>
            <p>Created at: {shoppingList.created_at}</p>
            <p>Updated at: {shoppingList.updated_at}</p>

        </div>
    );
}
