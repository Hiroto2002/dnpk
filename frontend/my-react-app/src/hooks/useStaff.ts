import { useCallback, useEffect, useState } from "react";
import { fetcher } from "../utils/fetcher";

export type Staff = {
  id: string;
  name: string;
};

export function useStaff() {
  const [staffs, setStaffs] = useState<Staff[]>([]);

  const fetchStaffs = useCallback(async () => {
    try {
      const result = await fetcher("StaffController", "getAll");
      setStaffs(result.staff.map((s) => ({ id: s.id, name: s.name })) ?? []);
    } catch (error) {
      console.error("Failed to fetch staffs", error);
    }
  }, []);

  useEffect(() => {
    void fetchStaffs();
  }, [fetchStaffs]);

  return {
    staffs,
    refetch: fetchStaffs,
  } as const;
}
