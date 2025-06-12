import ComboboxDinamis from '@/components/combobox-dinamis'
import { Button } from '@/components/ui/button'
import axios from 'axios'
import { Loader2, Search } from 'lucide-react'
import { useEffect, useState } from 'react'

type props = {
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    data: any
    setData: (data: any) => void
}

export default function Filters({
    processing,
    handleForm,
    data,
    setData,
}: props) {
    const [anggota, setAnggota] = useState("");
    useEffect(() => {
        setAnggota("")
    }, [open])
    return (
        <form onSubmit={handleForm} className="mb-4 flex w-1/2 items-end gap-4">
            <div className="flex-1">
                <ComboboxDinamis
                    label="anggota"
                    selectedValue={data.anggota}
                    fetchOptions={async (search) => {
                        const { data: options } = await axios.post(route('anggota.list'), { id: data.anggota, search });
                        if (!options.find((d: any) => d.value === data.anggota) && search !== '') {
                            setData((prev: any) => ({ ...prev, anggota: '' }));
                        }
                        return options;
                    }}
                    onSelect={(value, label) => {setData((prev: any) => ({ ...prev, anggota: value })); setAnggota(label)}}
                    search={anggota}
                    setSearch={setAnggota}
                />
            </div>
            <Button type="submit" disabled={processing}>
                {processing ? <Loader2 className="animate-spin" /> : <Search />}
                Cari
            </Button>
        </form>
    )
}
