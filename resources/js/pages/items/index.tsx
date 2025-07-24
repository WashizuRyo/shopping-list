import { Item } from '@/types';
import { Link, useForm } from '@inertiajs/react';

export default function ShoppingLists({ items }: { items: Item[] }) {
    const { delete: destroy, processing } = useForm();
    const handleDelete = (id: number) => {
        if (confirm('本当に削除しますか？')) {
            destroy(`/items/${id}`);
        }
    };

    return (
        <div>
            <h1>Items</h1>
            <ul>
                {items.map(({ name, id }) => (
                    <li key={id} className="mb-2 flex items-center gap-2">
                        <Link href={`/items/${id}`} className="text-blue-600 hover:text-blue-800 hover:underline">
                            {name}
                        </Link>
                        <Link href={`/items/${id}/edit`} className="text-blue-600 hover:text-blue-800 hover:underline">
                            Edit
                        </Link>
                        <button
                            onClick={() => handleDelete(id)}
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
