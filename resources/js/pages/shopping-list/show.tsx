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
            {shoppingList.items?.map((item) => (
                <div key={item.id}>
                    <h2>{item.name}</h2>
                    <p>Memo: {item.memo}</p>
                    <p>Quantity: {item.pivot?.quantity}</p>
                    <p>Checked: {item.pivot?.is_checked ? 'Yes' : 'No'}</p>
                </div>
            ))}
        </div>
    );
}
