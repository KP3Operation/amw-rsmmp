/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import UpsertFamilyPage from "./UpsertFamily.vue";

describe("UpsertFamilyPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(UpsertFamilyPage);
        expect(wrapper.exists()).toBe(true);
    });
});
