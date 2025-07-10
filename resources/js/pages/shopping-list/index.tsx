import { ShoppingList } from '@/types';
import { Link, useForm } from '@inertiajs/react';

export default function ShoppingLists({ shoppingLists }: { shoppingLists: ShoppingList[] }) {
    const { delete: destroy, processing } = useForm();
    const handleDelete = (id: number) => {
        if (confirm('本当に削除しますか？')) {
            destroy(`/shopping_lists/${id}`);
        }
    };

    return (
        <div>
            <h1>Shopping Lists</h1>
            <ul>
                {shoppingLists.map((list) => (
                    <li key={list.id} className="mb-2 flex items-center gap-2">
                        <Link href={`/shopping_lists/${list.id}`} className="text-blue-600 hover:text-blue-800 hover:underline">
                            {list.name}
                        </Link>
                        <Link href={`/shopping_lists/${list.id}/edit`} className="text-blue-600 hover:text-blue-800 hover:underline">
                            Edit
                        </Link>
                        <button
                            onClick={() => handleDelete(list.id)}
                            className="rounded bg-red-600 px-2 py-1 text-sm text-white hover:bg-red-700 disabled:opacity-50"
                            type="button"
                            disabled={processing}
                        >
                            削除
                        </button>
                    </li>
                ))}
            </ul>
        </div>
    );
}
